<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Turno;
use App\Models\Profesional;
use App\Models\Paciente;

class TurnoController extends Controller
{
    public function reservarTurno(Request $request)
    {
        // Obtener la fecha seleccionada o la fecha actual
        $fecha = $request->input('fecha', Carbon::today()->toDateString());

        // Obtener profesionales disponibles
        $profesionales = Profesional::all();

        // Generar horarios disponibles para la fecha actual
        $horariosDisponibles = $this->generarHorariosDisponibles($fecha);

        return view('turno', compact('fecha', 'horariosDisponibles', 'profesionales'));
    }


    public function guardarTurno(Request $request)
{
    // Validar los datos del formulario
    $request->validate([
        'fecha' => 'required|date|after_or_equal:today',
        'hora' => 'required',
        'profesional_id' => 'required|exists:profesionales,id',
    ]);

    // Combinar fecha y hora en un solo objeto Carbon
    $diaHora = Carbon::createFromFormat('Y-m-d H:i', $request->fecha . ' ' . $request->hora);

    // Verificar si el horario está disponible
    $existe = Turno::where('dia_hora', $diaHora)
                    ->where('estado', 'reservado')
                    ->exists();

    if ($existe) {
        return redirect()->back()->with('error', 'El horario seleccionado ya está reservado.');
    }

    // OBTENGO EL USER_ID DEL PACIENTE, ---NO EL ID----
    $paciente = Paciente::where('user_id', Auth::id())->first();

    if (!$paciente) {
        return redirect()->back()->with('error', 'No se encontró el paciente asociado al usuario.');
    }

    // Crear el turno
    Turno::create([
        'paciente_id' => $paciente->id, 
        'profesional_id' => $request->profesional_id,
        'dia_hora' => $diaHora,
        'estado' => 'reservado',
    ]);

    // Redirigir con éxito
    return redirect()->route('home')->with('success', 'Turno reservado con éxito.');
}

    



    // Método AJAX para obtener horarios disponibles para una fecha y profesional dados
    public function getHorariosDisponibles(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'profesional_id' => 'required|exists:profesionales,id',
        ]);

        // Obtener la fecha y el profesional
        $fecha = $request->input('fecha');
        $profesionalId = $request->input('profesional_id');

        // Generar los horarios disponibles
        $horariosDisponibles = $this->generarHorariosDisponibles($fecha);

        // Devolver los horarios disponibles en formato JSON
        return response()->json(['horariosDisponibles' => $horariosDisponibles]);
    }

    private function generarHorariosDisponibles($fecha)
{
    $horarios = [
        'manana' => [],
        'tarde' => [],
    ];

    $bloques = [
        ['nombre' => 'manana', 'inicio' => '08:30', 'fin' => '12:30'],
        ['nombre' => 'tarde', 'inicio' => '17:00', 'fin' => '20:00'],
    ];

    
    foreach ($bloques as $bloque) {
        $period = CarbonPeriod::create(
            Carbon::createFromFormat('Y-m-d H:i', "$fecha {$bloque['inicio']}"),
            '20 minutes',
            Carbon::createFromFormat('Y-m-d H:i', "$fecha {$bloque['fin']}")
        );

        foreach ($period as $time) {

            if ($time->format('H:i') === $bloque['fin']) {
                continue;
            }

            $horarios[$bloque['nombre']][] = $time->format('H:i');
        }
    }

    $reservados = Turno::whereDate('dia_hora', $fecha) // Filtrar  por la fecha (Y-m-d)
        ->where('estado', 'reservado') // Turnos reservados
        ->pluck('dia_hora') 
        ->map(function ($item) {
            return Carbon::parse($item)->format('H:i'); 
        })
        ->toArray();

    // HORARIOS DISPONIBLES, MENOS LOS RESERVADOS
    foreach (['manana', 'tarde'] as $turno) {
        $horarios[$turno] = array_values(array_diff($horarios[$turno], $reservados));
    }

    return $horarios;
}



    
}
