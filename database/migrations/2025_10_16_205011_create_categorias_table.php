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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // E.j., "Bebidas Calientes"
            $table->string('slug')->unique();   // E.j., "bebidas-calientes" (CRUCIAL para las URLs)
            $table->text('descripcion')->nullable(); 
            // Agrega el campo para la imagen de la categoría si lo usas en el menú principal
            $table->string('imagen_nombre')->nullable();
            $table->timestamps();
        });

        // 🚨 IMPORTANTE: También debes asegurarte de que tu tabla 'productos'
        // tenga la columna 'categoria_id' si aún no la has añadido.
        Schema::table('productos', function (Blueprint $table) {
            // Elimina esta línea si ya existe la columna
            $table->foreignId('categoria_id')->nullable()->constrained()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 🚨 IMPORTANTE: Primero elimina la clave foránea antes de eliminar la tabla 'productos'
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
        });
        
        Schema::dropIfExists('categorias');
    }
};
