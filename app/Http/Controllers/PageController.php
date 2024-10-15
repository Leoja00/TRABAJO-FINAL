<?php

namespace App\Http\Controllers;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\TurnoService;

class PageController extends Controller
{
    protected $turnoService;

    public function __construct(TurnoService $turnoService)
    {
        $this->turnoService = $turnoService;
    }

    public function home()
    {
        $this->turnoService->actualizarTurnosCompletados(); 

        return view('home');
    }

    public function actualizarTurnosCompletados()
{
    $argentinaTimezone = 'America/Argentina/Buenos_Aires';    
    $horaActual = Carbon::now($argentinaTimezone);
        $horaLimite = $horaActual->subHours(2);

    Turno::where('estado', 'reservado')
        ->where('dia_hora', '<=', $horaLimite)
        ->update(['estado' => 'completado']);
}



    public function showProfessionals()
    {
        return view('profesionales');
    }
}
