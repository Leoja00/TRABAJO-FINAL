<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); 
        return view('panel', compact('users')); 
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Verificar si el correo ya existe
    if (User::where('email', $request->email)->exists()) {
        return back()->withErrors([
            'email' => 'El correo electrónico ya está en uso.'
        ])->withInput();
    }

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'paciente',
        ]);

        // Verificar si el rol del usuario es paciente y agregarlo a la tabla 'pacientes'
        if ($user->role === 'paciente') {
            \DB::table('pacientes')->insert([
                'user_id' => $user->id,
                'obra_social' => null,
                'numero_afiliado' => null,
                'motivo_consulta' => 'Motivo de la consulta inicial',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Autenticar al usuario
        Auth::login($user);

        // Redirigir al inicio
        return redirect()->route('home')->with('message', 'Usuario registrado y sesión iniciada exitosamente.');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // El usuario ha iniciado sesión exitosamente
            return redirect()->route('app');
        }
    
        // Si las credenciales son incorrectas
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas son incorrectas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Cerrar la sesión del usuario
        return redirect()->route('home')->with('message', 'Has cerrado sesión exitosamente.');
    }
}
