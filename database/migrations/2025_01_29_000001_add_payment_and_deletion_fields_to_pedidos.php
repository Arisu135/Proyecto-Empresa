<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('metodo_pago')->nullable()->after('pagado');
            $table->boolean('eliminado')->default(false)->after('metodo_pago');
            $table->timestamp('eliminado_at')->nullable()->after('eliminado');
            $table->text('motivo_eliminacion')->nullable()->after('eliminado_at');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['metodo_pago', 'eliminado', 'eliminado_at', 'motivo_eliminacion']);
        });
    }
};
