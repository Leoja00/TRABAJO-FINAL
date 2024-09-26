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

    // Crear el usuario
    $user = User::create([
        'name' => $request->name, 
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'paciente',
    ]);

    if ($user) {

        \DB::table('pacientes')->insert([
            'user_id' => $user->id,
            'obra_social' => null,
            'numero_afiliado' => null,
            'motivo_consulta' => 'Motivo de la consulta inicial',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        return back()->withErrors(['register' => 'Error al registrar el usuario.']);
    }

    Auth::login($user);
    return redirect()->route('home')->with('message', 'Usuario registrado y sesión iniciada exitosamente.');
}



    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('app');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas son incorrectas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home')->with('message', 'Has cerrado sesión exitosamente.');
    }

    // Nuevo método para cambiar el rol de un usuario
    public function changeRole(Request $request, $id)
{
    $authUser = Auth::user();

    // Verificar que no sea un paciente quien intenta cambiar roles
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
        return view('perfilCompletar', compact('user'));
    }

//EDITAR PERFIL
public function editProfile()
{
    $user = Auth::user();
    return view('perfilEditar', compact('user'));
}

//ACTUALIZAR PERFIL
public function updateProfile(Request $request)
{
    $user = Auth::user();

    // Validar campos generales
    $request->validate([
        'telefono' => 'nullable|string',
        'fechaNacimiento' => 'nullable|date',
        'dni' => 'nullable|string|max:20',
        'direccion' => 'nullable|string|max:255',
        'especialidad' => 'nullable|string|max:100',
        'matricula' => 'nullable|string|max:50',
        'obra_social' => 'nullable|string|max:100',
        'numero_afiliado' => 'nullable|string|max:50',
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
        $user->profesional->save();
    } elseif ($user->role === 'paciente') {
        if ($request->filled('obra_social')) {
            $user->paciente->obra_social = $request->input('obra_social');
        }
        if ($request->filled('numero_afiliado')) {
            $user->paciente->numero_afiliado = $request->input('numero_afiliado');
        }
        $user->paciente->save();
    }

    // Guardar cambios del usuario
    $user->save();

    return redirect()->route('perfil.show')->with('success', 'Perfil actualizado exitosamente.');
}





}