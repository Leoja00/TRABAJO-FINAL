<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profesional;


class ProfesionalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profesional::create([
            'nombre' => 'Adriana',
            'apellido' => 'Mencarreli',
            'correo' => 'juan.perez@hospital.com',
            'contraseña' => bcrypt('123456789'),
            'telefono' => '123456789',
            'especialidad' => 'Médica Clínica',
            'matricula' => '12345',
            'fecha_nacimiento' => '1980-01-01',
            'dni' => '12345678',
            'direccion' => 'Santa Rosa del',
            'imagen' => 'img/profesionales/adriana.jpeg',
        ]);
        Profesional::create([
            'nombre' => 'Agustin',
            'apellido' => 'Talquenca',
            'correo' => 'danilo@gmail.com',
            'contraseña' => bcrypt('123456789'),
            'telefono' => '123456789',
            'especialidad' => 'Kinesiologo',
            'matricula' => '12345',
            'fecha_nacimiento' => '1980-01-01',
            'dni' => '422222',
            'direccion' => 'La Paz',
            'imagen' => 'img/profesionales/negro.png',
        ]);
    }
}
