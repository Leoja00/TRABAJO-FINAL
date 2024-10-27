<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PacienteNoLogueado;

class PacienteNoLogueadoController extends Controller
{
    public function registrar(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'dni' => 'required|string|max:20|unique:pacientes_no_logueados,dni',
        'obra_social' => 'nullable|string|max:255',
    ]);

    DB::table('pacientes_no_logueados')->insert([
        'dni' => $request->dni,
        'name' => $request->nombre,
        'obra_social' => $request->obra_social, 
        'telefono'=>$request->telefono,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json([
        'success' => true,
    ]);
}
public function completarCampos(Request $request)
{
    $request->validate([
        'dni' => 'required',
        'telefono' => 'nullable|string',
        'direccion' => 'nullable|string',
        'numero_afiliado' => 'nullable|string',
    ]);

    // Buscar paciente por DNI
    $paciente = PacienteNoLogueado::where('dni', $request->dni)->firstOrFail();

    // Actualizar campos si se proporcionan
    $paciente->telefono = $request->telefono ?? $paciente->telefono;
    $paciente->direccion = $request->direccion ?? $paciente->direccion;
    $paciente->numero_afiliado = $request->numero_afiliado ?? $paciente->numero_afiliado;
    $paciente->save();

    // Verificar si aún faltan campos
    if (!$paciente->telefono || !$paciente->direccion || !$paciente->numero_afiliado) {
        return response()->json(['success' => true, 'message' => 'Datos actualizados correctamente, pero aún faltan campos.']);
    }

    return response()->json(['success' => true, 'message' => 'Datos actualizados correctamente.']);
}




}
