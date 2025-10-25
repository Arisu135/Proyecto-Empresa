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
        Schema::create('pedido_detalles', function (Blueprint $table) {
            $table->id();

            // Claves foráneas
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos'); 
            
            // Datos del ítem
            $table->string('nombre_producto', 255);
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 8, 2);
            $table->decimal('subtotal', 8, 2);
            
            // Columna que faltaba y causaba el error
            // Usamos text para guardar el JSON de las opciones de personalización
            $table->text('opciones_personalizadas')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_detalles');
    }
};
