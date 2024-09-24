<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'obra_social',
        'numero_afiliado',
        'motivo_consulta',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
