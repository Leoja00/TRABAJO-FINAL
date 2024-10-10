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
        Schema::table('turnos', function (Blueprint $table) {
            // Agregar la columna dni_paciente_no_registrado
            $table->string('dni_paciente_no_registrado')->nullable()->after('paciente_id');
        
            // Modificar la columna paciente_id para que sea nullable
            $table->unsignedBigInteger('paciente_id')->nullable()->change();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('turnos', function (Blueprint $table) {
        // Eliminar la columna dni_paciente_no_registrado
        $table->dropColumn('dni_paciente_no_registrado');

        // Revertir la columna paciente_id a no nullable
        $table->unsignedBigInteger('paciente_id')->nullable(false)->change();
    });
}
};
