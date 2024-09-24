<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfesionalController;
use App\Http\Controllers\UserController; // Asegúrate de importar tu controlador de usuarios

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/profesionales', [ProfesionalController::class, 'index'])->name('profesionales');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');

Route::get('/cobertura', function () {
    return view('cobertura');
})->name('cobertura');

Route::get('/usuario', function () {
    return view('usuario');
})->name('usuario');

// Rutas para la autenticación
Route::get('/login', function () {
    return view('login'); // Asegúrate de tener esta vista
})->name('login');

Route::post('/login', [UserController::class, 'login'])->name('login.submit');

Route::get('/logout', [UserController::class, 'logout'])->name('logout'); // Ruta para cerrar sesión

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return redirect('/'); // Redirige a la raíz
    })->name('app');

    Route::get('/profesional/dashboard', [ProfesionalController::class, 'dashboard'])->name('profesional.dashboard'); // Ajusta según tu necesidad

    // Rutas para el administrador (ajusta según tus necesidades)
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });
});

Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('login', [UserController::class, 'login'])->name('login.submit');
Route::post('logout', [UserController::class, 'logout'])->name('logout');

Route::get('/admin/panel', [UserController::class, 'index'])->name('admin.panel')->middleware(middleware: 'auth');


Route::put('/admin/change-role/{id}', [UserController::class, 'changeRole'])->name(name: 'admin.changeRole');



Route::middleware(['auth', 'admin'])->group(function () {
Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');

});

