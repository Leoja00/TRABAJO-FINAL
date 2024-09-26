<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'fechaNacimiento',
        'dni',
        'direccion',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function profesional()
    {
        return $this->hasOne(Profesional::class);
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }

    public function secretario()
    {
        return $this->hasOne(Secretario::class);
    }
}

