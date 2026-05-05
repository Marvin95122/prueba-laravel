<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            if (!Schema::hasColumn('asistencias', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }

            if (!Schema::hasColumn('asistencias', 'resultado')) {
                $table->string('resultado')->default('permitido');
            }

            if (!Schema::hasColumn('asistencias', 'motivo')) {
                $table->string('motivo')->nullable();
            }

            if (!Schema::hasColumn('asistencias', 'fecha_hora')) {
                $table->dateTime('fecha_hora')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $columnas = [];

            foreach (['user_id', 'resultado', 'motivo', 'fecha_hora'] as $columna) {
                if (Schema::hasColumn('asistencias', $columna)) {
                    $columnas[] = $columna;
                }
            }

            if (!empty($columnas)) {
                $table->dropColumn($columnas);
            }
        });
    }
};