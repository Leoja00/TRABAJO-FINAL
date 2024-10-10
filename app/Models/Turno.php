<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'profesional_id',
        'secretario_id',
        'dia_hora',
        'estado',
        'dni_paciente_no_registrado',
    ];

    // Relaciones
    public function paciente()
{
    return $this->belongsTo(Paciente::class);
}

public function profesional()
{
    return $this->belongsTo(Profesional::class);
}

public function secretario()
{
    return $this->belongsTo(User::class, 'secretario_id');
}
}
