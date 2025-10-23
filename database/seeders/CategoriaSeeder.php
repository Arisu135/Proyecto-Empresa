<?php

namespace Database\Seeders; 

use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        // Limpieza de la tabla de categorías (PostgreSQL compatible)
        DB::statement('TRUNCATE TABLE categorias RESTART IDENTITY CASCADE;');

        $categorias = [
            'BEBIDAS CALIENTES', 
            'INFUSIONES', 
            'JUGOS', 
            'LIMONADAS',
            'BEBIDAS HELADAS', 
            'FRAPPE', 
            'COCTELES', 
            'POSTRES Y PASTELES',
            'WAFFLES', 
            'SALADOS', 
            'REBEL BUBBLES', 
            'CAFÉ',
        ];

        foreach ($categorias as $nombre) {
            // Genera un slug (ej: bebidas-calientes)
            $slug = Str::slug($nombre);

            // Genera el nombre de la imagen usando el slug, 
            // pero reemplazando los guiones (-) por guiones bajos (_)
            // (ej: bebidas_calientes.png)
            $imagenNombre = str_replace('-', '_', $slug) . '.png';

            Categoria::create([
                'nombre' => $nombre,
                'slug' => $slug,
                'imagen_nombre' => $imagenNombre, // <-- ¡ESTA ES LA LÍNEA CLAVE!
            ]);
        }
    }
}