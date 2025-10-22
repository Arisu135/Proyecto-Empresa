<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Añadido para manipular el esquema

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Desactivar y Reactivar la verificación de claves foráneas con comandos compatibles
        // En PostgreSQL, la forma más limpia de resetear tablas con FK es usando TRUNCATE con CASCADE.

        // NOTA: Para Heroku/PostgreSQL, usamos un TRUNCATE más fuerte
        // También es buena práctica limpiar las categorías si las vas a volver a crear.
        
        // Desactivamos la verificación de FK para este tipo de operaciones.
        // TRUNCATE ... RESTART IDENTITY CASCADE es la forma PostgreSQL de hacer un TRUNCATE limpio.
        if (Schema::hasTable('productos')) {
            DB::statement('TRUNCATE TABLE productos RESTART IDENTITY CASCADE;');
        }
        if (Schema::hasTable('categorias')) {
            DB::statement('TRUNCATE TABLE categorias RESTART IDENTITY CASCADE;');
        }
        
        // Si tu aplicación tiene tablas 'users' o 'pedidos' que deben limpiarse:
        // if (Schema::hasTable('users')) {
        //     DB::statement('TRUNCATE TABLE users RESTART IDENTITY CASCADE;');
        // }
        
        // Si tu aplicación requiere que se llamen otros seeders, puedes llamarlos aquí:
        // $this->call(CategoriasTableSeeder::class); // Si tienes un seeder de categorías
        
        // --- LÓGICA DE INSERCIÓN DEL CAFÉ (Asegúrate de que tus categorías existen antes) ---

        // Asumimos que la tabla de categorías ya tiene datos insertados antes de este punto
        // o que tu lógica de categorías está en otro seeder que llamas antes.

        // Obtener la ID de la categoría "BEBIDAS CALIENTES"
        $calientesId = Categoria::where('slug', 'bebidas-calientes')->value('id');

        // Insertar productos de la categoría BEBIDAS CALIENTES (ESPRESSO BAR)
        if ($calientesId) {
            
            // Espresso
            Producto::create([
                'nombre' => 'Espresso',
                'descripcion' => 'Café concentrado puro.',
                'precio' => 4.50,
                'categoria_id' => $calientesId, 
                'imagen_nombre' => 'espresso.png', 
            ]);

            // Café Americano
            Producto::create([
                'nombre' => 'Café Americano',
                'descripcion' => 'Café largo tradicional.',
                'precio' => 5.50,
                'categoria_id' => $calientesId,
                'imagen_nombre' => 'americano.png', 
            ]);

            // Capuccino
            Producto::create([
                'nombre' => 'Capuccino',
                'descripcion' => 'Café con leche texturizada y canela.',
                'precio' => 7.50,
                'categoria_id' => $calientesId,
                'imagen_nombre' => 'capuccino.png',
            ]);

            // ... (Continúa agregando el resto de los productos)
        }
    }
}