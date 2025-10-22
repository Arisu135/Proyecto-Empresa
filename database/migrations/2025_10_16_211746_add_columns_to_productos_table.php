<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Elimina la columna 'categoria' que es redundante y causa el error en PostgreSQL
     */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // VERIFICACIÓN CLAVE: Si la columna 'categoria' existe, la eliminamos.
            if (Schema::hasColumn('productos', 'categoria')) {
                $table->dropColumn('categoria');
            }
        });
    }

    /**
     * Revierte la migración (vuelve a añadir la columna 'categoria' si es necesario)
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Recreamos la columna 'categoria' como string y opcional (nullable)
            // Esto es solo para reversibilidad si se usa 'migrate:rollback'
            $table->string('imagen_nombre')->nullable(); 
        });
    }
};