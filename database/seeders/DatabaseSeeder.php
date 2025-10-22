<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. LIMPIAR LAS TABLAS con comandos compatibles con PostgreSQL
        if (Schema::hasTable('productos')) {
            DB::statement('TRUNCATE TABLE productos RESTART IDENTITY CASCADE;');
        }
        if (Schema::hasTable('categorias')) {
            DB::statement('TRUNCATE TABLE categorias RESTART IDENTITY CASCADE;');
        }
        
        // 2. INSERTAR CATEGORÍAS
        $categoriasData = [
            'BEBIDAS CALIENTES' => 'bebidas_calientes.jpg', 
            'INFUSIONES' => 'infusiones.jpg', 
            'JUGOS' => 'jugos.jpg', 
            'LIMONADAS' => 'limonadas.jpg',
            'BEBIDAS HELADAS' => 'bebidas_heladas.jpg', 
            'FRAPPE' => 'frappe.jpg', 
            'COCTELES' => 'cocteles.jpg', 
            'POSTRES Y PASTELES' => 'postres_pasteles.jpg',
            'WAFFLES' => 'waffles.jpg', 
            'SALADOS' => 'salados.jpg', 
            'REBEL BUBBLES' => 'rebel_bubbles.jpg', 
            'CAFÉ' => 'cafe.jpg'
        ];

        foreach ($categoriasData as $nombre => $imagen_nombre) {
            Categoria::create([
                'nombre' => $nombre,
                'slug' => Str::slug($nombre), 
                'imagen_nombre' => $imagen_nombre, // Asumiendo que tienes este campo
            ]);
        }
        
        // 3. OBTENER IDs DE CATEGORÍAS Y CONTINUAR CON LA INSERCIÓN DE PRODUCTOS

        $calientesId = Categoria::where('slug', 'bebidas-calientes')->value('id');
        $limonadasId = Categoria::where('slug', 'limonadas')->value('id');
        $jugosId = Categoria::where('slug', 'jugos')->value('id');

        // PRODUCTOS DE BEBIDAS CALIENTES
        if ($calientesId) {
            Producto::create([
                'nombre' => 'Espresso',
                'precio' => 8.50, // <-- CORRECCIÓN CLAVE
                'imagen_nombre' => 'espresso.jpg',
                'categoria_id' => $calientesId,
            ]);
            Producto::create([
                'nombre' => 'Cappuccino',
                'precio' => 12.00, // <-- CORRECCIÓN CLAVE
                'imagen_nombre' => 'cappuccino.jpg',
                'categoria_id' => $calientesId,
            ]);
        }
        
        // PRODUCTOS DE LIMONADAS
        if ($limonadasId) {
            Producto::create([
                'nombre' => 'Limonada Clásica',
                'precio' => 10.00, // <-- CORRECCIÓN CLAVE
                'imagen_nombre' => 'limonada_clasica.jpg',
                'categoria_id' => $limonadasId,
            ]);
            Producto::create([
                'nombre' => 'Limonada de Coco',
                'precio' => 15.00, // <-- CORRECCIÓN CLAVE
                'imagen_nombre' => 'limonada_coco.jpg',
                'categoria_id' => $limonadasId,
            ]);
        }

        // PRODUCTOS DE JUGOS
        if ($jugosId) {
            Producto::create([
                'nombre' => 'Jugo de Naranja',
                'precio' => 9.50, // <-- CORRECCIÓN CLAVE
                'imagen_nombre' => 'jugo_naranja.jpg',
                'categoria_id' => $jugosId,
            ]);
        }
        
        // Puedes añadir más lógica de seeder aquí si es necesario
        $this->command->info('Base de datos sembrada correctamente con Categorías y Productos.');
    }
}
