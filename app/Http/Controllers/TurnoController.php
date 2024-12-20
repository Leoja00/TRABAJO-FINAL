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
        $perPage = $isMobile ? 3 : 50;
    
        $turnos = Auth::user()->profesional->turnos()->with(['paciente'])->paginate($perPage);
    
        foreach ($turnos as $turno) {
            if (!$turno->paciente && $turno->dni_paciente_no_registrado) {
                // Buscar en la tabla 'pacientes_no_logueados' usando el DNI
                $pacienteNoRegistrado = DB::table('pacientes_no_logueados')
                    ->where('dni', $turno->dni_paciente_no_registrado)
                    ->first();
    
                if ($pacienteNoRegistrado) {
                    $turno->paciente_no_registrado_nombre = $pacienteNoRegistrado->name;
                    $turno->paciente_no_registrado_telefono = $pacienteNoRegistrado->telefono ?? 'No tiene';
    
                    // Verificar si el paciente no registrado tiene PAMI y contar sus turnos en el año
                    if ($pacienteNoRegistrado->obra_social === 'PAMI') {
                        $turno->turnosEnElAno = DB::table('turnos')
                            ->where('dni_paciente_no_registrado', $pacienteNoRegistrado->dni)
                            ->whereYear('dia_hora', now()->year)
                            ->count();
                    }
                } else {
                    $turno->paciente_no_registrado_nombre = 'No Registrado';
                    $turno->paciente_no_registrado_telefono = 'No tiene';
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
    

public function verPacientesHistorial()
{
    // Verificamos que el usuario esté autenticado y sea un profesional
    if (!Auth::check() || Auth::user()->role !== 'profesional') {
        return redirect()->route('home');
    }

    $isMobile = request()->header('User-Agent') && preg_match('/Mobile|Android|iPhone/', request()->header('User-Agent'));
    $perPage = $isMobile ? 3 : 50;

    $user = Auth::user();
    $turnos = $user->profesional->turnos()->with('paciente.user')->get();

    // Usamos un conjunto para almacenar pacientes únicos
    $pacientes = [];

    foreach ($turnos as $turno) {
        if ($turno->paciente) {
            // Para pacientes logueados, utilizamos el ID
            $paciente = $turno->paciente;
            $historiales = $paciente->historialClinicos; // Asegúrate de tener la relación definida

            $pacientes[$paciente->id] = (object) [
                'id' => $paciente->id,
                'name' => $paciente->user->name,
                'dni' => $paciente->user->dni,
                'obra_social' => $paciente->obra_social,
                'historiales' => $historiales,
            ];
        } elseif ($turno->dni_paciente_no_registrado) {
            $pacienteNoRegistrado = DB::table('pacientes_no_logueados')
                ->where('dni', $turno->dni_paciente_no_registrado)
                ->first();
    
            if ($pacienteNoRegistrado) {
                // Para pacientes no logueados, utilizamos el DNI como identificador
                $pacientes[$pacienteNoRegistrado->dni] = (object) [
                    'id' => $pacienteNoRegistrado->dni, 
                    'name' => $pacienteNoRegistrado->name,
                    'dni' => $pacienteNoRegistrado->dni,
                    'obra_social' => $pacienteNoRegistrado->obra_social,
                    'historiales' => [], // No hay historiales para pacientes no registrados
                ];
            }
        }
    }
    
    $pacientes = collect($pacientes)->values();
    $pacientesPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
        $pacientes->forPage(request()->input('page', 1), $perPage),
        $pacientes->count(),
        $perPage,
        request()->input('page', 1),
        ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('pacientesHistorial', compact('pacientesPaginated'));
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
            $turno->paciente_telefono = $paciente->telefono;

            // Solo contar turnos si la obra social es "PAMI"
            if ($paciente->obra_social === 'PAMI') {
                $turno->turnosEnElAno = $this->contarTurnosAnuales($paciente->id);
            } else {
                $turno->turnosEnElAno = null; // No mostrar cantidad de turnos si no es PAMI
            }
        } elseif ($turno->dni_paciente_no_registrado) {
            $pacienteNoRegistrado = DB::table('pacientes_no_logueados')
                ->where('dni', $turno->dni_paciente_no_registrado)
                ->first();

            if ($pacienteNoRegistrado) {
                $turno->paciente_no_registrado_nombre = $pacienteNoRegistrado->name;
                $turno->paciente_no_registrado_telefono = $pacienteNoRegistrado->telefono;

                // Solo contar turnos si la obra social es "PAMI"
                if ($pacienteNoRegistrado->obra_social === 'PAMI') {
                    $turno->turnosEnElAno = $this->contarTurnosAnualesNoRegistrados($turno->dni_paciente_no_registrado);
                } else {
                    $turno->turnosEnElAno = null; // No mostrar cantidad de turnos si no es PAMI
                }
            } else {
                $turno->paciente_no_registrado_nombre = 'No Registrado';
                $turno->paciente_no_registrado_telefono = 'No Disponible';
                $turno->turnosEnElAno = null;
            }
        }
    }

    return view('turnosTodosSecretarios', compact('turnos'));
}






public function verTurnos()
{
    $this->turnoService->actualizarTurnosCompletados();

    $isMobile = request()->header('User-Agent') && preg_match('/Mobile|Android|iPhone/', request()->header('User-Agent'));
    $perPage = $isMobile ? 3 : 50;

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
public function cancelarTurno($id)
{
    $turno = Turno::find($id);

    if (!$turno || $turno->estado !== 'reservado') {
        return redirect()->back()->with('error', 'El turno no se puede cancelar.');
    }

    // Eliminar el turno
    $turno->delete();

    return redirect()->route('turnos.ver')->with('success', 'Turno cancelado con éxito.');
}
public function cancelarTurnoSecretario($id)
{
    $turno = Turno::find($id);

    if (!$turno || $turno->estado !== 'reservado') {
        return redirect()->back()->with('error', 'El turno no se puede cancelar.');
    }

    // Eliminar el turno
    $turno->delete();

    return redirect()->route('turnos.secretario')->with('success', 'Turno cancelado con éxito.');
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

            return redirect()->back()->with('success', 'Turno guardado exitosamente');   
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


        return redirect()->back()->with('success', 'Turno guardado exitosamente');    }

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

        return redirect()->route('turnos.ver')->with('success', 'Turno reservado con éxito.');
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

    // Obtener todas las obras sociales desde la tabla 'obras_sociales'
    $obrasSociales = DB::table('obras_sociales')
        ->select('nombre')  // Suponiendo que el campo se llama 'nombre'
        ->pluck('nombre');  // Obtener solo los nombres de las obras sociales

    if ($usuario) {
        $paciente = Paciente::where('user_id', $usuario->id)->first();
        if ($paciente && $paciente->obra_social === 'PAMI') {
            $turnosEnElAno = $paciente->turnos()->whereYear('dia_hora', now()->year)->count();
        }

        return response()->json([
            'existe' => true,
            'nombre' => $usuario->name,
            'telefono' => $usuario->telefono, // Añadir el teléfono del usuario logueado
            'obraSocial' => $paciente->obra_social ?? null,
            'turnosEnElAno' => $turnosEnElAno,
            'obrasSociales' => $obrasSociales,  // Enviar las obras sociales al frontend
        ]);
    }

    // Verificar si el DNI está en la tabla de pacientes_no_logueados
    $pacienteNoLogueado = DB::table('pacientes_no_logueados')->where('dni', $dni)->first();
    if ($pacienteNoLogueado) {
        if ($pacienteNoLogueado->obra_social === 'PAMI') {
            $turnosEnElAno = $this->contarTurnosAnualesNoRegistrados($dni);
        }

        return response()->json([
            'existe' => true,
            'nombre' => $pacienteNoLogueado->name,
            'telefono' => $pacienteNoLogueado->telefono,  // Añadir el teléfono del paciente no logueado
            'obraSocial' => $pacienteNoLogueado->obra_social,
            'turnosEnElAno' => $turnosEnElAno,
            'obrasSociales' => $obrasSociales,  // Enviar las obras sociales al frontend
        ]);
    }

    return response()->json([
        'existe' => false,
        'registrar' => true,
        'obrasSociales' => $obrasSociales,  // Enviar las obras sociales al frontend
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
            /*POSIBLE CAMBIO EN UN FUTURO POR LO QUE ME DIJO LA DOCTORA
            case 1: // Adriana
                // Adriana todo el día (mañana y tarde) todos los días de la semana
                $bloques = [
                    ['nombre' => 'manana', 'inicio' => '08:30', 'fin' => '12:30'],
                    ['nombre' => 'tarde', 'inicio' => '17:00', 'fin' => '20:00'],
                ];
                break;*/
                case 1: // Adriana
                    if ($diaSemana === Carbon::MONDAY || $diaSemana === Carbon::WEDNESDAY) {
                        // Lunes y miércoles todo el día (mañana y tarde)
                        $bloques = [
                            ['nombre' => 'manana', 'inicio' => '08:30', 'fin' => '12:30'],
                            ['nombre' => 'tarde', 'inicio' => '17:00', 'fin' => '20:00'],
                        ];
                    } elseif ($diaSemana === Carbon::THURSDAY) {
                        // Jueves solo por la tarde
                        $bloques = [
                            ['nombre' => 'tarde', 'inicio' => '17:00', 'fin' => '20:00'],
                        ];
                    } elseif ($diaSemana === Carbon::FRIDAY) {
                        // Viernes solo por la mañana
                        $bloques = [
                            ['nombre' => 'manana', 'inicio' => '08:30', 'fin' => '12:30'],
                        ];
                    } else {
                        // Otros días no hay disponibilidad
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
