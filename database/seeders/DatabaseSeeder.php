<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Desactivar la verificación de claves foráneas temporalmente
        // Esto es necesario para hacer un TRUNCATE en una tabla con claves foráneas.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 2. Vaciar completamente la tabla 'productos' antes de insertar
        Producto::truncate(); 
        
        // 3. Reactivar la verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- LÓGICA DE INSERCIÓN DEL CAFÉ (QUE YA FUNCIONA) ---

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

            // ... (Continúa agregando el resto de los 9 productos que ya tenías)
        }
    }
}