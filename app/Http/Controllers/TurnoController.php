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
use App\Services\TurnoService;
use Illuminate\Support\Facades\DB;

class TurnoController extends Controller
{
    protected $turnoService;

    public function __construct(TurnoService $turnoService)
    {
        $this->turnoService = $turnoService;
    }

    public function verPacientesProfesional()
{
    $this->turnoService->actualizarTurnosCompletados();

    if (!Auth::check() || Auth::user()->role !== 'profesional') {
        return redirect()->route('home');
    }

    $isMobile = request()->header('User-Agent') && preg_match('/Mobile|Android|iPhone/', request()->header('User-Agent'));
    $perPage = $isMobile ? 3 : 10;

    $turnos = Auth::user()->profesional->turnos()->with(['paciente'])->paginate($perPage);

    foreach ($turnos as $turno) {
        if (!$turno->paciente && $turno->dni_paciente_no_registrado) {
            // Buscar en la tabla 'pacientes_no_logueados' usando el DNI
            $pacienteNoRegistrado = DB::table('pacientes_no_logueados')
                ->where('dni', $turno->dni_paciente_no_registrado)
                ->first();

            if ($pacienteNoRegistrado) {
                $turno->paciente_no_registrado_nombre = $pacienteNoRegistrado->name;

                // Verificar si el paciente no registrado tiene PAMI y contar sus turnos en el año
                if ($pacienteNoRegistrado->obra_social === 'PAMI') {
                    $turno->turnosEnElAno = DB::table('turnos')
                        ->where('dni_paciente_no_registrado', $pacienteNoRegistrado->dni)
                        ->whereYear('dia_hora', now()->year)
                        ->count();
                }
            } else {
                $turno->paciente_no_registrado_nombre = 'No Registrado';
                $turno->turnosEnElAno = 0;
            }
        } else {
            // Si el paciente está registrado y tiene PAMI, contar sus turnos en el año
            if ($turno->paciente && $turno->paciente->obra_social === 'PAMI') {
                $turno->turnosEnElAno = $turno->paciente->turnos()
                    ->whereYear('dia_hora', now()->year)
                    ->count();
            }
        }
    }

    return view('pacientesAdheridosProfesional', compact('turnos'));
}





    public function verTurnosSecretarios()
{
    if (!Auth::check() || Auth::user()->role !== 'secretario') {
        return redirect()->route('home');
    }

    $this->turnoService->actualizarTurnosCompletados();

    $turnos = Turno::with(['profesional', 'paciente', 'user'])->get();

    foreach ($turnos as $turno) {
        if ($turno->paciente) {
            $paciente = $turno->paciente;
            if ($paciente->obra_social === 'PAMI') {
                // Contar los turnos en el año
                $turno->turnosEnElAno = $this->contarTurnosAnuales($paciente->id);
            }
        } elseif ($turno->dni_paciente_no_registrado) {
            $pacienteNoRegistrado = DB::table('pacientes_no_logueados')
                ->where('dni', $turno->dni_paciente_no_registrado)
                ->first();

            if ($pacienteNoRegistrado) {
                $turno->paciente_no_registrado_nombre = $pacienteNoRegistrado->name;
                if ($pacienteNoRegistrado->obra_social === 'PAMI') {
                    $turno->turnosEnElAno = $this->contarTurnosAnualesNoRegistrados($turno->dni_paciente_no_registrado);
                }
            } else {
                $turno->paciente_no_registrado_nombre = 'No Registrado';
            }
        }
    }

    return view('turnosTodosSecretarios', compact('turnos'));
}




public function verTurnos()
{
    $this->turnoService->actualizarTurnosCompletados();

    $isMobile = request()->header('User-Agent') && preg_match('/Mobile|Android|iPhone/', request()->header('User-Agent'));
    $perPage = $isMobile ? 3 : 10;

    $user = Auth::user();
    $turnos = $user->paciente->turnos()->with('profesional')->paginate($perPage);

    // Contar los turnos en el año para pacientes con PAMI
    $turnosEnElAno = 0;
    if ($user->paciente->obra_social === 'PAMI') {
        $turnosEnElAno = $user->paciente->turnos()
            ->whereYear('dia_hora', now()->year)
            ->count();
    }

    return view('turnosSolicitadosUser', compact('turnos', 'turnosEnElAno'));
}


    public function reservarTurno(Request $request)
    {
        if (!Auth::check() || (Auth::user()->role !== 'secretario' && Auth::user()->role !== 'paciente')) {
            return redirect()->route('home');
        }


        $fecha = $request->input('fecha', Carbon::today()->toDateString());


        $profesionales = Profesional::all();

        $horariosDisponibles = $this->generarHorariosDisponibles($fecha, $request->profesional_id);

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
        $horariosDisponibles = $this->generarHorariosDisponibles($fecha, $request->profesional_id);

        // Devolver los horarios disponibles en formato JSON
        return response()->json(['horariosDisponibles' => $horariosDisponibles]);
    }

    public function verificarDni(Request $request)
    {
        $request->validate([
            'dni' => 'required|string',
        ]);

        $dni = $request->input('dni');


        $usuario = User::where('dni', $dni)->first();
        $turnosEnElAno = 0;

        if ($usuario) {

            $paciente = Paciente::where('user_id', $usuario->id)->first();
            if ($paciente && $paciente->obra_social === 'PAMI') {

                $turnosEnElAno = $paciente->turnos()->whereYear('dia_hora', now()->year)->count();
            }

            return response()->json([
                'existe' => true,
                'nombre' => $usuario->name,
                'obraSocial' => $paciente->obra_social ?? null,
                'turnosEnElAno' => $turnosEnElAno,
            ]);
        }

        // Verificar si el DNI está en la tabla de pacientes_no_logueados
        $pacienteNoLogueado = DB::table('pacientes_no_logueados')->where('dni', $dni)->first();
        if ($pacienteNoLogueado) {
            // Verificar si el paciente no registrado tiene PAMI
            if ($pacienteNoLogueado->obra_social === 'PAMI') {
                // Contar los turnos de pacientes no logueados
                $turnosEnElAno = $this->contarTurnosAnualesNoRegistrados($dni);
            }

            return response()->json([
                'existe' => true,
                'nombre' => $pacienteNoLogueado->name,
                'obraSocial' => $pacienteNoLogueado->obra_social,
                'turnosEnElAno' => $turnosEnElAno,
            ]);
        }

        // Si no se encuentra en ninguna tabla
        return response()->json([
            'existe' => false,
            'registrar' => true,
        ]);
    }

    // Función para contar los turnos de un paciente registrado en el año
    private function contarTurnosAnuales($userId)
    {
        return Turno::where('paciente_id', $userId)
            ->whereYear('dia_hora', now()->year)
            ->count();
    }

    // Función para contar los turnos de un paciente no registrado en el año
    private function contarTurnosAnualesNoRegistrados($dni)
    {
        return Turno::where('dni_paciente_no_registrado', $dni)
            ->whereYear('dia_hora', now()->year)
            ->count();
    }




    private function generarHorariosDisponibles($fecha, $profesionalId)
    {
        $horarios = [
            'manana' => [],
            'tarde' => [],
        ];
        date_default_timezone_set('America/Argentina/Buenos_Aires');



        $hoy = Carbon::today()->toDateString();
        $ahora = Carbon::now()->format('H:i');

        $diaSemana = Carbon::parse($fecha)->dayOfWeek;


        switch ($profesionalId) {
            case 1: // Adriana
                if (in_array($diaSemana, [Carbon::MONDAY, Carbon::WEDNESDAY])) {
                    // Adriana todo el día (mañana y tarde) LUNES Y MIERCOLES
                    $bloques = [
                        ['nombre' => 'manana', 'inicio' => '08:30', 'fin' => '12:30'],
                        ['nombre' => 'tarde', 'inicio' => '17:00', 'fin' => '20:00'],
                    ];
                } elseif ($diaSemana === Carbon::THURSDAY) {
                    // Adriana por la tarde JUEVES
                    $bloques = [
                        ['nombre' => 'tarde', 'inicio' => '17:00', 'fin' => '20:00'],
                    ];
                } elseif ($diaSemana === Carbon::FRIDAY) {
                    // Adriana por la mañana VIERNES
                    $bloques = [
                        ['nombre' => 'manana', 'inicio' => '08:30', 'fin' => '12:30'],
                    ];
                } else {
                    // No disponible en otros días
                    return $horarios;
                }
                break;


            case 3: // Psicólogo
                if ($diaSemana === Carbon::FRIDAY) {
                    // Psicólogo por la tarde (cada 45 minutos) VIERNES
                    $bloques = [
                        ['nombre' => 'tarde', 'inicio' => '17:00', 'fin' => '20:00', 'intervalo' => '45 minutes'],
                    ];
                } else {
                    return $horarios;
                }
                break;

            case 2: // Lars
                if ($diaSemana === Carbon::TUESDAY) {
                    // Lars por la tarde MARTES
                    $bloques = [
                        ['nombre' => 'tarde', 'inicio' => '17:30', 'fin' => '20:00'],
                    ];
                } else {
                    return $horarios;
                }
                break;

            default:

                return $horarios;
        }

        // Generar los periodos según los bloques definidos
        foreach ($bloques as $bloque) {
            $intervalo = isset($bloque['intervalo']) ? $bloque['intervalo'] : '15 minutes';

            $period = CarbonPeriod::create(
                Carbon::createFromFormat('Y-m-d H:i', "$fecha {$bloque['inicio']}"),
                $intervalo,
                Carbon::createFromFormat('Y-m-d H:i', "$fecha {$bloque['fin']}")
            );

            foreach ($period as $time) {
                // Saltar solo los horarios pasados si es el día actual
                if ($fecha === $hoy && $time->format('H:i') <= $ahora) {
                    continue; // Saltar los horarios anteriores a la hora actual
                }

                // Excluir la hora final del bloque
                if ($time->format('H:i') === $bloque['fin']) {
                    continue;
                }

                $horarios[$bloque['nombre']][] = $time->format('H:i');
            }
        }

        // Filtrar los horarios reservados
        $reservados = Turno::whereDate('dia_hora', $fecha)
            ->where('profesional_id', $profesionalId) // Filtrar por profesional
            ->where('estado', 'reservado')
            ->pluck('dia_hora')
            ->map(function ($item) {
                return Carbon::parse($item)->format('H:i');
            })
            ->toArray();

        // Eliminar horarios reservados de los disponibles
        foreach (['manana', 'tarde'] as $turno) {
            $horarios[$turno] = array_values(array_diff($horarios[$turno], $reservados));
        }

        return $horarios;
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



}
