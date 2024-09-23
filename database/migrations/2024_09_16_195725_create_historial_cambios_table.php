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
        Schema::create('historial_cambios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');  // Quién hizo el cambio
            $table->string('tabla_modificada');  // Tabla donde se hizo el cambio
            $table->string('tipo_cambio');  // 'insert', 'update', 'delete'
            $table->json('datos_anteriores')->nullable();  // Datos antes del cambio
            $table->json('datos_nuevos')->nullable();  // Datos después del cambio
            $table->timestamps();  // Cuándo se hizo el cambio
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_cambios');
    }
};
