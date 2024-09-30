<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesional extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'especialidad',
        'matricula',
        'imagen',
    ];

    // Definir el nombre correcto de la tabla
    protected $table = 'profesionales';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
