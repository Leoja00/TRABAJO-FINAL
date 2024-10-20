<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Turno;

class TurnoService
{
    public function actualizarTurnosCompletados()
    {
        $argentinaTimezone = 'America/Argentina/Buenos_Aires';
        $horaActual = Carbon::now($argentinaTimezone);
        $horaLimite = $horaActual->subHours(2);

        Turno::where('estado', 'reservado')
            ->where('dia_hora', '<=', $horaLimite)
            ->update(['estado' => 'completado']);
    }
}
