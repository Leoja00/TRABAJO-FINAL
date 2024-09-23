<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo')->unique();
            $table->string('contraseña');
            $table->string('telefono');
            $table->unsignedBigInteger('profesional_id');
            $table->foreign('profesional_id')->references('id')->on('profesionales');
            $table->date('fecha_nacimiento');
            $table->string('direccion');
            $table->string('dni')->unique();
            $table->string('obra_social')->nullable();
            $table->string('numero_afiliado')->nullable();
            $table->string('motivo_consulta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
