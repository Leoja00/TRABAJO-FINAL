<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\Profesional;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PacienteController extends Controller
{
    /**
     * Mostrar el formulario para reservar un turno.
     */
    public function reservarTurno(Request $request)
    {
        // Obtener la fecha seleccionada o la fecha actual
        $fecha = $request->input('fecha', Carbon::today()->toDateString());

        // Obtener profesionales (si aplica)
        $profesionales = Profesional::all();

        // Generar horarios disponibles
        $horariosDisponibles = $this->generarHorariosDisponibles($fecha);

        return view('turno', compact('fecha', 'horariosDisponibles', 'profesionales'));
    }

    /**
     * Procesar la reserva del turno.
     */
    public function guardarTurno(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'profesional_id' => 'required|exists:profesionales,id',
        ]);

        // Combinar fecha y hora
        $diaHora = Carbon::createFromFormat('Y-m-d H:i', $request->fecha . ' ' . $request->hora);

        // Verificar si el horario está disponible
        $existe = Turno::where('dia_hora', $diaHora)
                        ->where('estado', 'reservado')
                        ->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'El horario seleccionado ya está reservado.');
        }

        // Crear el turno
        Turno::create([
            'paciente_id' => Auth::id(), 
            'profesional_id' => $request->profesional_id,
            'dia_hora' => $diaHora,
            'estado' => 'reservado',
        ]);

        return redirect()->route('turno.sacar')->with('success', 'Turno reservado exitosamente.');
    }

    /**
     * Generar horarios disponibles para una fecha dada.
     */
    private function generarHorariosDisponibles($fecha)
    {
        $horarios = [];

        // Definir los bloques de tiempo
        $bloques = [
            ['inicio' => '09:00', 'fin' => '12:00'],
            ['inicio' => '14:30', 'fin' => '19:00'],
        ];

        foreach ($bloques as $bloque) {
            $period = CarbonPeriod::create(
                Carbon::createFromFormat('Y-m-d H:i', "$fecha {$bloque['inicio']}"),
                '30 minutes',
                Carbon::createFromFormat('Y-m-d H:i', "$fecha {$bloque['fin']}")
            );

            foreach ($period as $time) {
                // Evitar el último intervalo que coincide con el fin
                if ($time->format('H:i') === $bloque['fin']) {
                    continue;
                }

                $horarios[] = $time->format('H:i');
            }
        }

        // Obtener los horarios ya reservados para la fecha
        $reservados = Turno::whereDate('dia_hora', $fecha)
                           ->where('estado', 'reservado')
                           ->pluck('dia_hora')
                           ->map(function ($item) {
                               return Carbon::parse($item)->format('H:i');
                           })
                           ->toArray();

        // Filtrar los horarios disponibles
        $disponibles = array_diff($horarios, $reservados);

        return $disponibles;
    }
}
