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
        Schema::table('productos', function (Blueprint $table) {
            // AÑADIMOS LA COLUMNA FALTANTE
            $table->string('imagen_nombre')->nullable()->after('categoria_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // ELIMINAMOS LA COLUMNA SI HACEMOS ROLLBACK
            $table->dropColumn('imagen_nombre');
        });
    }
};
