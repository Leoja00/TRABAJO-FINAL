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
        Schema::create('historial_clinico', function (Blueprint $table) {
            $table->id();
            // Relaciones con paciente y paciente_no_logueado
            $table->unsignedBigInteger('paciente_id')->nullable();
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('cascade');
        
            $table->unsignedBigInteger('paciente_no_logueado_id')->nullable();
            $table->foreign('paciente_no_logueado_id')->references('id')->on('pacientes_no_logueados')->onDelete('cascade');
        
            // Relación con profesional
            $table->unsignedBigInteger('profesional_id');
            $table->foreign('profesional_id')->references('id')->on('profesionales')->onDelete('cascade');
        
            // Información de la atención
            $table->string('tension_arterial')->nullable(); // mm/Hg
            $table->float('peso')->nullable(); // Peso en kilogramos
            $table->text('motivo_consulta')->nullable();
            $table->text('datos_relevantes_examen_fisico')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('tratamiento_indicaciones')->nullable();
        
            // Documentación (puede ser un JSON o un campo para almacenar URLs de imágenes)
            $table->json('documentacion')->nullable();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_clinico');
    }
};

