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
        Schema::table('posventa_detalles', function (Blueprint $table) {
            $table->double('producto_compra')->after('producto_nombre')->default(0);
            $table->double('producto_costo_compra')->after('producto_cantidad')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posventa_detalles', function (Blueprint $table) {
            $table->dropColumn('producto_compra');
            $table->dropColumn('producto_costo_compra');
        });
    }
};
