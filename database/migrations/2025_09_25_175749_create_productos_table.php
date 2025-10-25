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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Combo 6 piezas, Bucket, etc.
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 8, 2);
            $table->enum('categoria', ['Combos', 'Individual', 'Bebidas', 'Postres']);
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};

