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
        $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
        $table->foreignId('producto_id')->constrained('productos'); 
        $table->string('nombre_producto', 255);
        $table->unsignedInteger('cantidad');
        $table->decimal('precio_unitario', 8, 2);
        $table->decimal('subtotal', 8, 2);

        // 💡 AÑADIMOS la columna faltante aquí, ya que estamos creando la tabla
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

