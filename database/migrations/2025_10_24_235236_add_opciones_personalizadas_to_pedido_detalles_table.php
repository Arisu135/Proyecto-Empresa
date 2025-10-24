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
        Schema::table('pedido_detalles', function (Blueprint $table) {
            // Añade la nueva columna 'opciones_personalizadas' después de 'subtotal'.
            // Usamos 'text' para guardar el JSON de las opciones y 'nullable' para flexibilidad.
            $table->text('opciones_personalizadas')->nullable()->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_detalles', function (Blueprint $table) {
            // Esto elimina la columna si se revierte la migración.
            $table->dropColumn('opciones_personalizadas');
        });
    }
};