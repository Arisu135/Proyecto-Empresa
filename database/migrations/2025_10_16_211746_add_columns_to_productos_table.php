<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Elimina la columna 'categoria' que es redundante y causa el error en PostgreSQL.
     */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Acción: Eliminar la columna 'categoria'
            if (Schema::hasColumn('productos', 'categoria')) {
                $table->dropColumn('categoria');
            }
        });
    }

    /**
     * Revierte la migración (vuelve a añadir la columna 'categoria').
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Acción de reversibilidad: Recrear la columna 'categoria'
            $table->string('categoria')->nullable(); 
        });
    }
};
