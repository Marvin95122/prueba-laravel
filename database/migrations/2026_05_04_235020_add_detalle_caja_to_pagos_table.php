<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            if (!Schema::hasColumn('pagos', 'folio')) {
                $table->string('folio')->nullable();
            }

            if (!Schema::hasColumn('pagos', 'membresia_id')) {
                $table->unsignedBigInteger('membresia_id')->nullable();
            }

            if (!Schema::hasColumn('pagos', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }

            if (!Schema::hasColumn('pagos', 'tipo_pago')) {
                $table->string('tipo_pago')->default('otro');
            }

            if (!Schema::hasColumn('pagos', 'monto_recibido')) {
                $table->decimal('monto_recibido', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('pagos', 'cambio')) {
                $table->decimal('cambio', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('pagos', 'referencia')) {
                $table->string('referencia')->nullable();
            }

            if (!Schema::hasColumn('pagos', 'estado')) {
                $table->string('estado')->default('pagado');
            }

            if (!Schema::hasColumn('pagos', 'fecha_pago')) {
                $table->dateTime('fecha_pago')->nullable();
            }

            if (!Schema::hasColumn('pagos', 'notas')) {
                $table->text('notas')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $columnas = [];

            foreach ([
                'folio',
                'membresia_id',
                'user_id',
                'tipo_pago',
                'monto_recibido',
                'cambio',
                'referencia',
                'estado',
                'fecha_pago',
                'notas',
            ] as $columna) {
                if (Schema::hasColumn('pagos', $columna)) {
                    $columnas[] = $columna;
                }
            }

            if (!empty($columnas)) {
                $table->dropColumn($columnas);
            }
        });
    }
};