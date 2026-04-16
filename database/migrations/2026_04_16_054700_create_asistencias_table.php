<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
    Schema::create('asistencias', function (Blueprint $table) {
        $table->id();
        // Relación con la tabla clientes
        $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
        $table->timestamps(); // Esto guardará automáticamente la fecha y hora de entrada
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
