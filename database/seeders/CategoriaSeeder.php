<?php

namespace Database\Seeders; 

use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'BEBIDAS CALIENTES', 'INFUSIONES', 'JUGOS', 'LIMONADAS',
            'BEBIDAS HELADAS', 'FRAPPE', 'COCTELES', 'POSTRES Y PASTELES',
            'WAFFLES', 'SALADOS', 'REBEL BUBBLES', 'CAFÉ',
        ];

        foreach ($categorias as $nombre) {
            Categoria::create([
                'nombre' => $nombre,
                'slug' => Str::slug($nombre), // Genera el slug automáticamente
            ]);
        }
    }
}