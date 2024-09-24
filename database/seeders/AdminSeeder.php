<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verifica si ya existe un usuario admin para evitar duplicados
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@example.com', 
                'password' => bcrypt('password123'), 
                'role' => 'admin',
            ]);
        }
    }
}
