<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('folio')->nullable()->unique()->after('id');
            $table->foreignId('membresia_id')->nullable()->after('cliente_id')->constrained('membresias')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->after('membresia_id')->constrained('users')->nullOnDelete();
            $table->string('tipo_pago')->default('otro')->after('concepto');
            $table->dateTime('fecha_pago')->nullable()->after('metodo_pago');
            $table->text('notas')->nullable()->after('fecha_pago');
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropUnique(['folio']);
            $table->dropConstrainedForeignId('membresia_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['folio', 'tipo_pago', 'fecha_pago', 'notas']);
        });
    }
};