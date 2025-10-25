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
        // 🚨 CAMBIO A SINGULAR: Es la causa más común de fallo de nombre en Laravel/PostgreSQL.
        // Si la tabla original se llama 'pedido_detalle', esto funcionará.
        Schema::table('pedido_detalle', function (Blueprint $table) {
            $table->text('opciones_personalizadas')->nullable()->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_detalle', function (Blueprint $table) {
            $table->dropColumn('opciones_personalizadas');
        });
    }
};