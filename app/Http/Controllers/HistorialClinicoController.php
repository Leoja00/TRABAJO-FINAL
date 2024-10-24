<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\HistorialClinico;
use App\Models\Paciente;
use App\Models\PacienteNoLogueado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
class HistorialClinicoController extends Controller
{
    public function destroy($id)
{
    $historial = HistorialClinico::findOrFail($id);
    $historial->delete();
    return redirect()->back()->with('success', 'Historial clínico eliminado correctamente.');
}

    public function crear($paciente_id)
{
    $profesionalAutenticado = Auth::user()->profesional;

    if (!$profesionalAutenticado) {
        // Abortamos con un error 403 (Prohibido) si no hay profesional asociado
        abort(403, 'Acceso denegado: No se encontró el profesional asociado.');
    }
    $paciente = Paciente::find($paciente_id);
    $pacienteNoLogueado = DB::table('pacientes_no_logueados')->where('dni', $paciente_id)->first();

    if (!$paciente && !$pacienteNoLogueado) {
        abort(404);
    }
    $historiales = HistorialClinico::where(function($query) use ($paciente, $pacienteNoLogueado) {
                                            if ($paciente) {
                                                $query->where('paciente_id', $paciente->id);
                                            } elseif ($pacienteNoLogueado) {
                                                $query->where('paciente_no_logueado_id', $pacienteNoLogueado->id);
                                            }
                                        })
                                        ->where('profesional_id', $profesionalAutenticado->id) 
                                        ->get(['id', 'tension_arterial', 'peso', 'motivo_consulta', 'datos_relevantes_examen_fisico', 'diagnostico', 'tratamiento_indicaciones', 'documentacion', 'created_at']);

    $turnos = collect();
    if ($paciente) {
        $turnos = $paciente->turnos()->where('profesional_id', $profesionalAutenticado->id)
                                      ->with(['paciente'])
                                      ->get(); 
    } elseif ($pacienteNoLogueado) {
        $turnos = DB::table('turnos')->where('dni_paciente_no_registrado', $pacienteNoLogueado->dni)
                                     ->where('profesional_id', $profesionalAutenticado->id) 
                                     ->get();
        foreach ($turnos as $turno) {
            $turno->paciente_no_registrado_nombre = $pacienteNoLogueado->name;
        }
    }

    // Retornar la vista con los datos
    return view('historialClinica', [
        'paciente' => $paciente,
        'pacienteNoLogueado' => $pacienteNoLogueado,
        'turnos' => $turnos,
        'historiales' => $historiales,
        'profesional_id' => $profesionalAutenticado->id, // ID del profesional autenticado
    ]);
}







    public function actualizar(Request $request, $id)
{
    $validatedData = $request->validate([
        'tension_arterial' => 'nullable|string',
        'peso' => 'nullable|numeric',
        'motivo_consulta' => 'nullable|string',
        'datos_relevantes_examen_fisico' => 'nullable|string',
        'diagnostico' => 'nullable|string',
        'tratamiento_indicaciones' => 'nullable|string',
        'documento.*' => 'nullable|file|mimes:jpg,png,pdf,jpeg', 
    ]);

    $historial = HistorialClinico::findOrFail($id);

    if ($request->filled('tension_arterial')) {
        $historial->tension_arterial = $request->tension_arterial;
    }

    if ($request->filled('peso')) {
        $historial->peso = $request->peso;
    }

    if ($request->filled('motivo_consulta')) {
        $historial->motivo_consulta = $request->motivo_consulta;
    }

    if ($request->filled('datos_relevantes_examen_fisico')) {
        $historial->datos_relevantes_examen_fisico = $request->datos_relevantes_examen_fisico;
    }

    if ($request->filled('diagnostico')) {
        $historial->diagnostico = $request->diagnostico;
    }

    if ($request->filled('tratamiento_indicaciones')) {
        $historial->tratamiento_indicaciones = $request->tratamiento_indicaciones;
    }

    $rutaCarpeta = public_path('img/historial_documentos');
    if (!file_exists($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0755, true);
    }

    $rutasDocumentos = [];
    if ($request->hasFile('documento')) {
        foreach ($request->file('documento') as $documento) {
            
            $nombreArchivo = time() . '_' . $documento->getClientOriginalName();
            $documento->move($rutaCarpeta, $nombreArchivo);
            $rutasDocumentos[] = 'img/historial_documentos/' . $nombreArchivo;
        }

    
        $documentacionExistente = json_decode($historial->documentacion, true) ?? [];
        $historial->documentacion = json_encode(array_merge($documentacionExistente, $rutasDocumentos));
    }
    $historial->save();

    return redirect()->back()->with('success', 'El historial clínico ha sido actualizado.');
}

public function descargarTodosHistorialesPDF($paciente_id)
{
    $profesionalAutenticado = Auth::user()->profesional;

    if (!$profesionalAutenticado) {
        abort(403, 'No tienes acceso a este historial clínico.');
    }

    $profesional_id = $profesionalAutenticado->id;
    $paciente = Paciente::find($paciente_id);
    $pacienteNoLogueado = PacienteNoLogueado::where('dni', $paciente_id)->first();

    if (!$paciente && !$pacienteNoLogueado) {
        return response()->json(['error' => 'Paciente no encontrado'], 404);
    }

    $historiales = HistorialClinico::where('profesional_id', $profesional_id)
        ->where(function($query) use ($paciente, $pacienteNoLogueado) {
            if ($paciente) {
                $query->where('paciente_id', $paciente->id);
            } elseif ($pacienteNoLogueado) {
                $query->where('paciente_no_logueado_id', $pacienteNoLogueado->id);
            }
        })
        ->with('profesional')
        ->get();

    if ($historiales->isEmpty()) {
        abort(403, 'No tienes acceso a este historial clínico.');
    }

    // Manejar las imágenes de la documentación
    foreach ($historiales as $historial) {
        $historial->documentacion = json_decode($historial->documentacion, true);
    }

    $turnos = collect();
    if ($paciente) {
        $turnos = $paciente->turnos()->where('profesional_id', $profesional_id)->with(['profesional'])->get();
    } elseif ($pacienteNoLogueado) {
        $turnos = DB::table('turnos')->where('dni_paciente_no_registrado', $pacienteNoLogueado->dni)
                                     ->where('profesional_id', $profesional_id)
                                     ->get();
    }

    $pdf = Pdf::loadView('historiales.todos_pdf', [
        'paciente' => $paciente,
        'pacienteNoLogueado' => $pacienteNoLogueado,
        'turnos' => $turnos,
        'historiales' => $historiales,
    ]);

    return $pdf->download('historias_clinicas_paciente_' . ($paciente->id ?? $pacienteNoLogueado->dni) . '.pdf');
}


   

public function guardar(Request $request)
{
    // Validar los datos entrantes
    $validatedData = $request->validate([
        'paciente_id' => 'nullable|exists:pacientes,id',
        'paciente_no_logueado_id' => 'nullable|exists:pacientes_no_logueados,id',
        'tension_arterial' => 'nullable|string',
        'peso' => 'nullable|numeric',
        'motivo_consulta' => 'nullable|string',
        'datos_relevantes_examen_fisico' => 'nullable|string',
        'diagnostico' => 'nullable|string',
        'tratamiento_indicaciones' => 'nullable|string',
        'documento.*' => 'nullable|file|mimes:pdf,jpeg,png,jpg', 
    ]);

    
    $profesionalAutenticado = Auth::user()->profesional; 
    if (!$profesionalAutenticado) {
        return redirect()->back()->with('error', 'No se encontró el profesional asociado.');
    }
    $validatedData['profesional_id'] = $profesionalAutenticado->id; 
    // Crear la carpeta para documentos si no existe
    $rutaCarpeta = public_path('img/historial_documentos');
    if (!file_exists($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0755, true);
    }
    $rutasDocumentos = [];
    if ($request->hasFile('documento')) {
        foreach ($request->file('documento') as $documento) {
            $nombreArchivo = time() . '_' . $documento->getClientOriginalName();
            $documento->move($rutaCarpeta, $nombreArchivo);
            $rutasDocumentos[] = 'img/historial_documentos/' . $nombreArchivo;
        }
    }

    $validatedData['documentacion'] = json_encode($rutasDocumentos);

    // Crear el historial clínico
    HistorialClinico::create($validatedData);

    return redirect()->back()->with('success', 'El historial clínico ha sido guardado.');
}


}
