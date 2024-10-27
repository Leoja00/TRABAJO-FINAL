<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ObraSocialController;
use App\Models\ObraSocial;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); 
        return view('panel', compact('users')); 
    }

    public function register(Request $request)
{
    // Validación del registro
    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'regex:/^[\pL\s]+$/u', 'max:255'], // Solo letras y espacios
        'email' => 'required|string|email|max:255|unique:users', // Verifica que el correo sea único
        'password' => 'required|string|min:6|confirmed', // Confirmed verifica que password_confirmation coincida
    ], [
        'name.regex' => 'El nombre solo puede contener letras y espacios.',
        'email.unique' => 'El correo ya está registrado.', 
        'password.confirmed' => 'Las contraseñas no son iguales.',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator, 'register')->withInput()->with('signupError', true);
    }

    // Crea el usuario
    $user = User::create([
        'name' => $request->name, 
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'paciente',
    ]);

    if ($user) {
        // tabla "pacientes"
        \DB::table('pacientes')->insert([
            'user_id' => $user->id,
            'obra_social' => null,
            'numero_afiliado' => null,
            'motivo_consulta' => 'Motivo de la consulta inicial',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        return back()->withErrors(['register' => 'Error al registrar el usuario.'], 'register')->with('signupError', true);
    }

    Auth::login($user);
    return redirect()->route('home')->with('message', 'Usuario registrado y sesión iniciada exitosamente.');
}




public function login(Request $request)
{
    // Valida el campo email y password
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    // Verifica si el correo existe
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        // Si el correo no existe
        return back()->withErrors([
            'email' => 'Correo no registrado.',
        ], 'login')->with('signupError', false);
    }

    // Correo existe, pero la contraseña es incorrecta
    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors([
            'password' => 'Las credenciales proporcionadas son incorrectas.',
        ], 'login')->with('signupError', false);
    }

    // Login exitoso
    return redirect()->route('app');
}



    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home')->with('message', 'Has cerrado sesión exitosamente.');
    }

    public function changeRole(Request $request, $id)
{
    $authUser = Auth::user();

    
    if ($authUser->role === 'paciente') {
        return redirect()->back()->withErrors(['unauthorized' => 'No tienes permiso para realizar esta acción.']);
    }

    $user = User::findOrFail($id);

  
    $validatedData = $request->validate([
        'role' => 'required|in:paciente,profesional,secretario'
    ]);

    $newRole = $validatedData['role'];
    $currentRole = $user->role;


    if ($newRole === $currentRole) {
        return redirect()->back()->withErrors(['role' => 'El rol ya está asignado.']);
    }

    if ($currentRole === 'profesional') {
        \DB::table('profesionales')->where('user_id', $user->id)->delete();
    } elseif ($currentRole === 'secretario') {
        \DB::table('secretarios')->where('user_id', $user->id)->delete();
    } elseif ($currentRole === 'paciente') {
        \DB::table('pacientes')->where('user_id', $user->id)->delete();
    }

   
    $user->role = $newRole;
    $user->save();

    // AGREGA A LA TABLA CON EL ROL
    if ($newRole === 'profesional') {
        \DB::table('profesionales')->insert([
            'user_id' => $user->id,
            'especialidad' => null, 
            'matricula'=> null,
            'imagen'=> null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } elseif ($newRole === 'secretario') {
        \DB::table('secretarios')->insert([
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } elseif ($newRole === 'paciente') {
        \DB::table('pacientes')->insert([
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->back()->with('success', 'Rol del usuario actualizado y movido a la tabla correspondiente.');
}

//VER PERFIL
public function showProfile()
{
    $user = Auth::user();
    return view('perfil', compact('user'));
}

public function completarPerfil()
    {
        $user = Auth::user();
    $obrasSociales = ObraSocial::all(); // Todas las obras sociales
    return view('perfilCompletar', compact('user', 'obrasSociales'));
    }

//EDITAR PERFIL
public function editProfile()
{
    $user = Auth::user();
    $obrasSociales = ObraSocial::all(); // Todas las obras sociales
    return view('perfilEditar', compact('user' , 'obrasSociales'));
}

//ACTUALIZAR PERFIL
public function updateProfile(Request $request)
{
    $user = Auth::user();

    // Validar campos generales
    $request->validate([
        'telefono' => 'nullable|numeric|digits_between:6,10',
        'fechaNacimiento' => 'nullable|date|before:today',
        'dni' => 'nullable|numeric|digits_between:7,9',
        'direccion' => 'nullable|string|min:5|max:40',
        'especialidad' => 'nullable|string|max:100',
        'matricula' => 'nullable|string|max:50',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'obra_social' => 'nullable|string|max:100',
        'numero_afiliado' => 'nullable|numeric|digits_between:8,10|required_if:obra_social,!SIN OBRA SOCIAL',
    ]);

    // Guardar campos generales solo si no están vacíos
    if ($request->filled('telefono')) {
        $user->telefono = $request->input('telefono');
    }
    if ($request->filled('fechaNacimiento')) {
        $user->fechaNacimiento = $request->input('fechaNacimiento');
    }
    if ($request->filled('dni')) {
        $user->dni = $request->input('dni');
    }
    if ($request->filled('direccion')) {
        $user->direccion = $request->input('direccion');
    }

    // Guardar campos específicos por rol
    if ($user->role === 'profesional') {
        if ($request->filled('especialidad')) {
            $user->profesional->especialidad = $request->input('especialidad');
        }
        if ($request->filled('matricula')) {
            $user->profesional->matricula = $request->input('matricula');
        }
        if ($request->hasFile('imagen')) {
            // Si hay imagen previa, eliminarla
            if ($user->profesional->imagen && file_exists(public_path($user->profesional->imagen))) {
                unlink(public_path($user->profesional->imagen));
            }

            // Guardar nueva imagen
            $image = $request->file('imagen');
            $imageName = time() . '-' . $image->getClientOriginalName(); 
            $imagePath = 'img/profesionales/' . $imageName;
            $image->move(public_path('img/profesionales'), $imageName);
            $user->profesional->imagen = $imagePath;
        }
        $user->profesional->save();
    }
    elseif ($user->role === 'paciente') {
        if ($request->filled('obra_social')) {
            $user->paciente->obra_social = $request->input('obra_social');
        }

        // Si la obra social es SIN PREPAGA, dejar numero_afiliado en NULL
        if ($request->input('obra_social') === 'SIN OBRA SOCIAL') {
            $user->paciente->numero_afiliado = null;
        } else if ($request->filled('numero_afiliado')) {
            $user->paciente->numero_afiliado = $request->input('numero_afiliado');
        }
        
        $user->paciente->save();
    }

    // Guardar cambios del usuario
    $user->save();

    return redirect()->route('perfil.show')->with('success', 'Perfil actualizado exitosamente.');
}






}