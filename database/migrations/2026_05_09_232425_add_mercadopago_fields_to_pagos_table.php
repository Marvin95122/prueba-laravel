<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            if (!Schema::hasColumn('pagos', 'mp_preference_id')) {
                $table->string('mp_preference_id')->nullable()->after('folio');
            }

            if (!Schema::hasColumn('pagos', 'mp_payment_id')) {
                $table->string('mp_payment_id')->nullable()->after('mp_preference_id');
            }

            if (!Schema::hasColumn('pagos', 'mp_external_reference')) {
                $table->string('mp_external_reference')->nullable()->after('mp_payment_id');
            }

            if (!Schema::hasColumn('pagos', 'mp_init_point')) {
                $table->text('mp_init_point')->nullable()->after('mp_external_reference');
            }

            if (!Schema::hasColumn('pagos', 'mp_sandbox_init_point')) {
                $table->text('mp_sandbox_init_point')->nullable()->after('mp_init_point');
            }

            if (!Schema::hasColumn('pagos', 'mp_status')) {
                $table->string('mp_status')->nullable()->after('mp_sandbox_init_point');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $columns = [];

            foreach ([
                'mp_preference_id',
                'mp_payment_id',
                'mp_external_reference',
                'mp_init_point',
                'mp_sandbox_init_point',
                'mp_status',
            ] as $column) {
                if (Schema::hasColumn('pagos', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};