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
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('cascade');
            
            $table->unsignedBigInteger('profesional_id');
            $table->foreign('profesional_id')->references('id')->on('profesionales')->onDelete('cascade');
            
            $table->unsignedBigInteger('secretario_id')->nullable();
            $table->foreign('secretario_id')->references('id')->on('secretarios')->onDelete('set null');
            
            $table->dateTime('dia_hora');
            $table->enum('estado', ['disponible', 'reservado', 'completado']);
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
