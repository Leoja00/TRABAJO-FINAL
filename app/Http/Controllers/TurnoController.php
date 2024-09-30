<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

class TurnoController extends Controller
{
    public function reservarTurno(Request $request)
    {
        // Obtener la fecha seleccionada o usar la fecha actual
        $fecha = $request->input('fecha') ?? now()->format('Y-m-d');

        // Obtener turnos disponibles para la fecha seleccionada
        $turnosMañana = Turno::whereDate('dia_hora', $fecha)
                              ->where('estado', 'disponible')
                              ->whereTime('dia_hora', '<', '12:00:00')
                              ->get();

        $turnosTarde = Turno::whereDate('dia_hora', $fecha)
                             ->where('estado', 'disponible')
                             ->whereTime('dia_hora', '>=', '12:00:00')
                             ->get();

        return view('turno', compact('turnosMañana', 'turnosTarde'));
    }

    /**
     * Procesar la reserva de un turno.
     */
    public function guardarTurno(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'turno' => 'required|exists:turnos,id',
        ]);

        // Encontrar el turno seleccionado
        $turno = Turno::find($request->input('turno'));

        // Verificar si el turno está disponible
        if ($turno->estado !== 'disponible') {
            return redirect()->route('turno.sacar')->with('error', 'El turno ya no está disponible.');
        }

        // Actualizar el estado del turno y asociarlo al paciente
        $turno->estado = 'reservado';
        $turno->paciente_id = Auth::user()->paciente->id;
        $turno->save();

        return redirect()->route('turno.sacar')->with('success', 'Turno reservado exitosamente.');
    }

}
