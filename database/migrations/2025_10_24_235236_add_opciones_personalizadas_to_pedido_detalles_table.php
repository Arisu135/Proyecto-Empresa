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
        // 🚨 CAMBIO CLAVE: Usamos el nombre 'pedido_detalles' pero con mayúsculas y minúsculas
        // para asegurarnos de que PostgreSQL lo encuentre, si fue creado con camelCase.
        // Si tu tabla fue creada con un nombre diferente (ej: 'order_items'), cámbialo aquí.
        // Nota: Laravel usa comillas dobles para PostgreSQL, así que no es necesario aquí.
        
        Schema::table('pedido_detalles', function (Blueprint $table) {
            $table->text('opciones_personalizadas')->nullable()->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_detalles', function (Blueprint $table) {
            $table->dropColumn('opciones_personalizadas');
        });
    }
};