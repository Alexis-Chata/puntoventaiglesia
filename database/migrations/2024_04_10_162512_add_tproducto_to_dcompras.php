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
        Schema::table('dcompras', function (Blueprint $table) {
            $table->string('tipo_producto')->nullable()->default('estandar')->after('nombre_producto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dcompras', function (Blueprint $table) {
            $table->dropColumn('tipo_producto');
        });
    }
};