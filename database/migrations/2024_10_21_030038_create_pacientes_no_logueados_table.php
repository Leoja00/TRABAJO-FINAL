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
        Schema::create('pacientes_no_logueados', function (Blueprint $table) {
            $table->id();
            $table->string('dni'); 
            $table->string('name');
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('obra_social')->nullable();
            $table->string('numero_afiliado')->nullable(); 
            $table->string('motivo_consulta')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes_no_logueados');
    }
};
