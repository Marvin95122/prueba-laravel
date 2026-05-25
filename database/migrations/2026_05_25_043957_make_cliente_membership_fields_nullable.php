<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('membresia')->nullable()->change();
            $table->date('vigencia_hasta')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('membresia')->nullable(false)->change();
            $table->date('vigencia_hasta')->nullable(false)->change();
        });
    }
};