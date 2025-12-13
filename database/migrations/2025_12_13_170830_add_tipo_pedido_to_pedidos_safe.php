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
        Schema::table('pedidos', function (Blueprint $table) {
            if (!Schema::hasColumn('pedidos', 'tipo_pedido')) {
                $table->string('tipo_pedido')->default('dine_in')->after('estado');
            }
            if (!Schema::hasColumn('pedidos', 'numero_mesa')) {
                $table->string('numero_mesa')->nullable()->after('direccion');
            }
            if (!Schema::hasColumn('pedidos', 'pagado')) {
                $table->boolean('pagado')->default(false)->after('estado');
            }
            if (!Schema::hasColumn('pedidos', 'metodo_pago')) {
                $table->string('metodo_pago')->nullable()->after('pagado');
            }
            if (!Schema::hasColumn('pedidos', 'impreso')) {
                $table->boolean('impreso')->default(false)->after('metodo_pago');
            }
            if (!Schema::hasColumn('pedidos', 'eliminado')) {
                $table->boolean('eliminado')->default(false)->after('impreso');
            }
            if (!Schema::hasColumn('pedidos', 'eliminado_at')) {
                $table->timestamp('eliminado_at')->nullable()->after('eliminado');
            }
            if (!Schema::hasColumn('pedidos', 'motivo_eliminacion')) {
                $table->text('motivo_eliminacion')->nullable()->after('eliminado_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $columns = ['tipo_pedido', 'numero_mesa', 'pagado', 'metodo_pago', 'impreso', 'eliminado', 'eliminado_at', 'motivo_eliminacion'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pedidos', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
