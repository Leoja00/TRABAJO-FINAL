<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PacienteNoLogueado extends Model
{
    use HasFactory;

    protected $table = 'pacientes_no_logueados'; // Nombre de la tabla

    protected $fillable = [
        'dni',
        'name',
        'telefono',
        'direccion',
        'obra_social',
        'numero_afiliado',
        'motivo_consulta',
    ];

    // Relación opcional si deseas asociar turnos a pacientes no logueados
    public function turnos()
    {
        return $this->hasMany(Turno::class, 'dni_paciente_no_registrado', 'dni');
    }
}
