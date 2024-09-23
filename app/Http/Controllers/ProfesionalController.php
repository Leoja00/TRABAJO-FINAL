<?php

namespace App\Http\Controllers;

use App\Models\Profesional;
use Illuminate\Http\Request;

class ProfesionalController extends Controller
{
    public function index()
    {
        // Busca todos los profesionales
        $profesionales = Profesional::all();
    
        // Enviar los profesionales a la vista
        return view('profesionales', compact('profesionales'));
    }
}
