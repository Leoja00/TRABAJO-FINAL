<?php

namespace App\Http\Controllers;
use App\Models\ObraSocial;


use Illuminate\Http\Request;

class ObraSocialController extends Controller
{
    public function index()
    {
        // Busca todos los profesionales
        $obras_sociales = ObraSocial::all();
    
        // Enviar los profesionales a la vista
        return view('cobertura', compact('obras_sociales'));
    }
}
