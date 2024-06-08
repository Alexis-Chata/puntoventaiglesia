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
        Schema::table('configuracions', function (Blueprint $table) {
            $table->string('sistema')->nullable();
            $table->string('servicio_id')->nullable();
            $table->string('ancho_impresion')->nullable()->default(215.25);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuracions', function (Blueprint $table) {
            $table->dropColumn('sistema');
            $table->dropColumn('servicio_id');
            $table->dropColumn('ancho_impresion');
        });
    }
};
