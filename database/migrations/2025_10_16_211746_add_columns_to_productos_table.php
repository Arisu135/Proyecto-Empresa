<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ...

public function up(): void
{
    Schema::table('productos', function (Blueprint $table) {
        // 1. ELIMINAR ESTA LÍNEA, ya que 'categoria_id' ya existe en tu DB
        // $table->foreignId('categoria_id')->nullable()->constrained()->after('id'); 
        
        // 2. SOLO DEJAR la columna 'imagen_nombre'
        $table->string('imagen_nombre')->nullable();
    });
}

// ...

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('productos', function (Blueprint $table) {
        // Eliminar clave foránea y columna 'categoria_id'
        // ELIMINAR ESTAS LÍNEAS TAMBIÉN
        // $table->dropForeign(['categoria_id']);
        // $table->dropColumn('categoria_id');
        
        // Mantener solo la eliminación de la columna que estamos añadiendo
        $table->dropColumn('imagen_nombre');
    });
}
};