<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json([
        'success' => true,
    ]);
}


}
