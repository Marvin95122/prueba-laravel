<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            if (!Schema::hasColumn('pagos', 'monto_recibido')) {
                $table->decimal('monto_recibido', 10, 2)->nullable()->after('monto');
            }

            if (!Schema::hasColumn('pagos', 'cambio')) {
                $table->decimal('cambio', 10, 2)->default(0)->after('monto_recibido');
            }

            if (!Schema::hasColumn('pagos', 'referencia')) {
                $table->string('referencia')->nullable()->after('tipo_pago');
            }

            if (!Schema::hasColumn('pagos', 'estado')) {
                $table->string('estado')->default('pagado')->after('referencia');
            }

            if (!Schema::hasColumn('pagos', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('estado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $columnas = [];

            foreach (['monto_recibido', 'cambio', 'referencia', 'estado', 'observaciones'] as $col) {
                if (Schema::hasColumn('pagos', $col)) {
                    $columnas[] = $col;
                }
            }

            if (!empty($columnas)) {
                $table->dropColumn($columnas);
            }
        });
    }
};