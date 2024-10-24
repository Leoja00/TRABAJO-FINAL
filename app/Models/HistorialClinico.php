<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialClinico extends Model
{
    use HasFactory;
    protected $table = 'historial_clinico';

    // Define los atributos que son asignables en masa
    protected $fillable = [
        'paciente_id',
        'paciente_no_logueado_id',
        'profesional_id',
        'tension_arterial',
        'peso',
        'motivo_consulta',
        'datos_relevantes_examen_fisico',
        'diagnostico',
        'tratamiento_indicaciones',
        'documentacion',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function pacienteNoLogueado()
    {
        return $this->belongsTo(PacienteNoLogueado::class, 'paciente_no_logueado_id');
    }

    public function profesional()
    {
        return $this->belongsTo(Profesional::class, 'profesional_id');
    }
}
