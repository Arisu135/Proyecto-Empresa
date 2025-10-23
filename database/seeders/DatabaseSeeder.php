<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// No necesitamos Producto, Categoria, DB, Schema, o Str aquí, ya que solo orquestamos.

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Llamar al Seeder de Categorías primero (IMPORTANTE para obtener IDs)
        // ASUMIMOS que tienes un archivo CategoriaSeeder.php que crea las categorías.
        $this->call([
            // CategoriaSeeder debe crear las tablas y las categorías.
            CategoriaSeeder::class, 
            
            // 2. Llamar al Seeder de Productos con la lista completa.
            ProductoSeeder::class,
        ]);
        
        $this->command->info('Base de datos sembrada completamente con Categorías y Productos.');
    }
}
