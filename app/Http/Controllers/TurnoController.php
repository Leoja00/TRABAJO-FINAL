<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Turno;
use App\Models\Profesional;
use App\Models\Paciente;
use App\Models\User;
use App\Models\Secretario;

class TurnoController extends Controller
{
    public function reservarTurno(Request $request)
    {

        $fecha = $request->input('fecha', Carbon::today()->toDateString());

        
        $profesionales = Profesional::all();

        $horariosDisponibles = $this->generarHorariosDisponibles($fecha);

        return view('turno', compact('fecha', 'horariosDisponibles', 'profesionales'));
    }

    public function guardarTurno(Request $request)
{
    // Reglas de validación
    $rules = [
        'fecha' => 'required|date|after_or_equal:today',
        'hora' => 'required',
        'profesional_id' => 'required|exists:profesionales,id',
    ];

    // Validación extra para el secretario (DNI)
    if (Auth::user()->role === 'secretario') {
        $rules['dni'] = 'required|string';
    }

    // Validar los datos
    $request->validate($rules);

    
    try {
        $diaHora = Carbon::createFromFormat('Y-m-d H:i', $request->fecha . ' ' . $request->hora);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Formato de fecha u hora inválido.');
    }

    // Verificar si el turno ya está reservado
    $turnoExistente = Turno::where('dia_hora', $diaHora)
        ->where('estado', 'reservado')
        ->exists();

    if ($turnoExistente) {
        return redirect()->back()->with('error', 'El horario seleccionado ya está reservado.');
    }

   
    $user = Auth::user();

    // Proceso específico para el rol de secretario
    if ($user->role === 'secretario') {
        return $this->guardarTurnoSecretario($request, $diaHora, $user);
    }

    // Proceso para paciente (usuario común)
    return $this->guardarTurnoPaciente($request, $diaHora, $user);
}

/**
 * Guardar turno para el rol de secretario
 */
protected function guardarTurnoSecretario(Request $request, $diaHora, $user)
{
    $dni = $request->input('dni');

    // Buscar usuario por DNI
    $usuario = User::where('dni', $dni)->first();

    if ($usuario) {
        // Verificar si el usuario tiene un paciente asociado
        $paciente = Paciente::where('user_id', $usuario->id)->first();
    
        if (!$paciente) {
            return redirect()->back()->with('error', 'El usuario con el DNI ingresado no tiene un paciente asociado.');
        }
    
        // Obtener el secretario asociado al usuario actual
        $secretario = Secretario::where('user_id', $user->id)->first();
    
        if (!$secretario) {
            return redirect()->back()->with('error', 'El usuario actual no tiene un secretario asociado.');
        }
    
        // Guardar turno con paciente existente
        Turno::create([
            'secretario_id' => $secretario->id, // Usar el ID del secretario
            'paciente_id' => $paciente->id,
            'profesional_id' => $request->profesional_id,
            'dia_hora' => $diaHora,
            'estado' => 'reservado',
        ]);
    
        return redirect()->route('home')->with('success', 'Turno reservado con éxito para ' . $usuario->name . ' por el secretario.');
    }
    
    // Guardar turno para un DNI no registrado
    $secretario = Secretario::where('user_id', $user->id)->first();
    if (!$secretario) {
        return redirect()->back()->with('error', 'El usuario actual no tiene un secretario asociado.');
    }
    
    Turno::create([
        'secretario_id' => $secretario->id, 
        'dni_paciente_no_registrado' => $dni,
        'profesional_id' => $request->profesional_id,
        'dia_hora' => $diaHora,
        'estado' => 'reservado',
    ]);
    

    return redirect()->route('home')->with('success', 'Turno reservado con éxito para DNI: ' . $dni . ' por el secretario.');
}

/**
 * Guardar turno para el rol de paciente (usuario común)
 */
protected function guardarTurnoPaciente(Request $request, $diaHora, $user)
{
    // Verificar si el paciente está asociado al usuario
    $paciente = Paciente::where('user_id', $user->id)->first();

    if (!$paciente) {
        return redirect()->back()->with('error', 'No se encontró el paciente asociado al usuario.');
    }

    // Guardar turno con el paciente
    Turno::create([
        'paciente_id' => $paciente->id,
        'profesional_id' => $request->profesional_id,
        'dia_hora' => $diaHora,
        'estado' => 'reservado',
    ]);

    return redirect()->route('home')->with('success', 'Turno reservado con éxito.');
}


    public function getHorariosDisponibles(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'profesional_id' => 'required|exists:profesionales,id',
        ]);

        $fecha = $request->input('fecha');
        $profesionalId = $request->input('profesional_id');

        // Generar los horarios disponibles
        $horariosDisponibles = $this->generarHorariosDisponibles($fecha);

        // Devolver los horarios disponibles en formato JSON
        return response()->json(['horariosDisponibles' => $horariosDisponibles]);
    }

    // Método para verificar si un DNI existe en la tabla users
    public function verificarDni(Request $request)
    {
        $request->validate([
            'dni' => 'required|string',
        ]);

        $dni = $request->input('dni');

        $usuario = User::where('dni', $dni)->first();

        if ($usuario) {
            return response()->json([
                'existe' => true,
                'nombre' => $usuario->name,
            ]);
        } else {
            return response()->json([
                'existe' => false,
            ]);
        }
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

        $hoy = Carbon::today()->toDateString();
        $ahora = Carbon::now()->subHours(3)->format('H:i');
        //echo "<script>console.log('$ahora');</script>";

        foreach ($bloques as $bloque) {
            $period = CarbonPeriod::create(
                Carbon::createFromFormat('Y-m-d H:i', "$fecha {$bloque['inicio']}"),
                '20 minutes',
                Carbon::createFromFormat('Y-m-d H:i', "$fecha {$bloque['fin']}")
            );

            foreach ($period as $time) {
                if ($fecha === $hoy && $time->format('H:i') <= $ahora) {
                    continue;
                }

                if ($time->format('H:i') === $bloque['fin']) {
                    continue;
                }

                $horarios[$bloque['nombre']][] = $time->format('H:i');
            }
        }

        $reservados = Turno::whereDate('dia_hora', $fecha) // Filtrar por la fecha (Y-m-d)
            ->where('estado', 'reservado') // Turnos reservados
            ->pluck('dia_hora')
            ->map(function ($item) {
                return Carbon::parse($item)->format('H:i');
            })
            ->toArray();

        // Horarios disponibles, menos los reservados
        foreach (['manana', 'tarde'] as $turno) {
            $horarios[$turno] = array_values(array_diff($horarios[$turno], $reservados));
        }

        return $horarios;
    }
}
