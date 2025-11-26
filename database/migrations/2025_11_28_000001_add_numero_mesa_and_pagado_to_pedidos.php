<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('numero_mesa')->nullable()->after('direccion');
            $table->boolean('pagado')->default(false)->after('estado');
            $table->string('tipo_pedido')->default('mesa')->after('pagado');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['numero_mesa', 'pagado', 'tipo_pedido']);
        });
    }
};
