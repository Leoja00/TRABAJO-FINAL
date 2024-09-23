<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\ProfesionalController;

class Profesional extends Model
{
    use HasFactory;
    protected $table = 'profesionales';

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'contraseña',
        'telefono',
        'especialidad',
        'matricula',
        'fecha_nacimiento',
        'dni',
        'direccion',
        'imagen',
    ];

    protected $hidden = [
        'contraseña',  // Para ocultar la contraseña en las respuestas JSON
    ];

    // Relación con Turno: Un profesional tiene muchos turnos
    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }
}
