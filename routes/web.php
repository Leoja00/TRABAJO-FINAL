<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfesionalController;


Route::get('/', function () {
    return view('home');
    
})->name('home');


Route::get('/profesionales', function () {
    return view('profesionales');
})->name('profesionales');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');

Route::get('/cobertura', function () {
    return view('cobertura');
})->name('cobertura');

Route::get('/usuario', function () {
    return view('usuario');
})->name('usuario');

Route::get('/profesionales', [ProfesionalController::class, 'index'])->name('profesionales');





