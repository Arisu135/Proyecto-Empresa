<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str; // Añadir para usar Str::slug

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
        
        // 2. INSERTAR CATEGORÍAS (¡Paso Faltante!)
        $categoriasData = [
            'BEBIDAS CALIENTES', 'INFUSIONES', 'JUGOS', 'LIMONADAS',
            'BEBIDAS HELADAS', 'FRAPPE', 'COCTELES', 'POSTRES Y PASTELES',
            'WAFFLES', 'SALADOS', 'REBEL BUBBLES', 'CAFÉ'
        ];

        foreach ($categoriasData as $nombre) {
            Categoria::create([
                'nombre' => $nombre,
                'slug' => Str::slug($nombre), // Crea el slug para las rutas
                // 'imagen' => 'img/categorias/' . Str::slug($nombre) . '.jpg', // Opcional si la guardas en DB
            ]);
        }
        
        // 3. CONTINUAR CON LA INSERCIÓN DE PRODUCTOS (Tu código existente)

        // Obtener la ID de la categoría "BEBIDAS CALIENTES" (Ahora existe)
        $calientesId = Categoria::where('slug', 'bebidas-calientes')->value('id');

        // ... (Tu lógica de inserción de productos que ya me enviaste)
        if ($calientesId) {
            // Espresso
            Producto::create([
                'nombre' => 'Espresso',
                // ... el resto de campos
            ]);
            // ... otros productos
        }
    }
}