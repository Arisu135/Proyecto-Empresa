<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 1. Limpieza de la tabla de productos (PostgreSQL compatible)
        DB::statement('TRUNCATE TABLE productos RESTART IDENTITY CASCADE;');

        // --- Mapeo de Categorías por Slug ---
        // Esto asegura que obtendremos el ID correcto.
        $categorias = Categoria::pluck('id', 'slug');

        // ===================================================================
        // 1. BEBIDAS CALIENTES (Slug: bebidas-calientes)
        // ===================================================================
        $calientesId = $categorias['bebidas-calientes'] ?? null;

        if ($calientesId) {
            // NOTA: Se ha eliminado la clave 'categoria' para evitar el error 42703
            Producto::create(['nombre' => 'Espresso', 'descripcion' => 'Café concentrado puro.', 'precio' => 4.50, 'disponible' => 1, 'categoria_id' => $calientesId, 'imagen_nombre' => 'espresso.png']);
            Producto::create(['nombre' => 'Café Americano', 'descripcion' => 'Café largo tradicional.', 'precio' => 5.50, 'disponible' => 1, 'categoria_id' => $calientesId, 'imagen_nombre' => 'americano.png']);
            Producto::create(['nombre' => 'Capuccino', 'descripcion' => 'Café con leche texturizada y canela.', 'precio' => 7.50, 'disponible' => 1, 'categoria_id' => $calientesId, 'imagen_nombre' => 'capuccino.png']);
            Producto::create(['nombre' => 'Cafe Mocca', 'descripcion' => 'Café, leche y chocolate.', 'precio' => 8.50, 'disponible' => 1, 'categoria_id' => $calientesId, 'imagen_nombre' => 'cafe_mocca.png']);
            Producto::create(['nombre' => 'Café Bombom', 'descripcion' => 'Café con leche condensada.', 'precio' => 8.50, 'disponible' => 1, 'categoria_id' => $calientesId, 'imagen_nombre' => 'cafe_bombom.png']);
            Producto::create(['nombre' => 'Matcha Milk', 'descripcion' => 'Té matcha con leche, caliente o frío.', 'precio' => 8.50, 'disponible' => 1, 'categoria_id' => $calientesId, 'imagen_nombre' => 'matcha_milk.png']);
            Producto::create(['nombre' => 'Submarino', 'descripcion' => 'Leche caliente con barra de chocolate.', 'precio' => 8.00, 'disponible' => 1, 'categoria_id' => $calientesId, 'imagen_nombre' => 'submarino.png']);
            Producto::create(['nombre' => 'Chocolate Caliente', 'descripcion' => 'Chocolate espeso tradicional.', 'precio' => 7.50, 'disponible' => 1, 'categoria_id' => $calientesId, 'imagen_nombre' => 'chocolate_caliente.png']);
            Producto::create(['nombre' => 'Leche', 'descripcion' => 'Vaso de leche caliente o fría.', 'precio' => 5.00, 'disponible' => 1, 'categoria_id' => $calientesId, 'imagen_nombre' => 'leche.png']);
        }
        
        // ===================================================================
        // 2. INFUSIONES (Slug: infusiones)
        // ===================================================================
        $infusionesId = $categorias['infusiones'] ?? null;
        if ($infusionesId) {
            Producto::create(['nombre' => 'Frutos Rojos', 'descripcion' => 'Infusión natural de frutos rojos, caliente.', 'precio' => 8.00, 'disponible' => 1, 'categoria_id' => $infusionesId, 'imagen_nombre' => 'frutos_rojos.png']);
            Producto::create(['nombre' => 'Verde Selva', 'descripcion' => 'Té verde aromático con hierbas de la selva.', 'precio' => 8.00, 'disponible' => 1, 'categoria_id' => $infusionesId, 'imagen_nombre' => 'verde_selva.png']);
            Producto::create(['nombre' => 'Mate', 'descripcion' => 'Infusión tradicional de hierba mate.', 'precio' => 4.00, 'disponible' => 1, 'categoria_id' => $infusionesId, 'imagen_nombre' => 'mate.png']);
        }
        
        // ===================================================================
        // 3. JUGOS (Slug: jugos)
        // ===================================================================
        $jugosId = $categorias['jugos'] ?? null;
        if ($jugosId) {
            Producto::create(['nombre' => 'Zumo de Naranja', 'descripcion' => 'Jugo fresco de naranja natural.', 'precio' => 7.00, 'disponible' => 1, 'categoria_id' => $jugosId, 'imagen_nombre' => 'zumo_naranja.png']);
            Producto::create(['nombre' => 'Jugo de Fresa', 'descripcion' => 'Jugo de fresa con agua o leche.', 'precio' => 7.00, 'disponible' => 1, 'categoria_id' => $jugosId, 'imagen_nombre' => 'jugo_fresa.png']);
            Producto::create(['nombre' => 'Jugo de Platano', 'descripcion' => 'Jugo de plátano con leche.', 'precio' => 7.00, 'disponible' => 1, 'categoria_id' => $jugosId, 'imagen_nombre' => 'jugo_platano.png']);
            Producto::create(['nombre' => 'Smoothie de Arandanos', 'descripcion' => 'Batido de arándanos y base de yogurt.', 'precio' => 9.50, 'disponible' => 1, 'categoria_id' => $jugosId, 'imagen_nombre' => 'smoothie_arandanos.png']);
        }
        
        // ===================================================================
        // 4. LIMONADAS (Slug: limonadas)
        // ===================================================================
        $limonadasId = $categorias['limonadas'] ?? null;
        if ($limonadasId) {
            Producto::create(['nombre' => 'Limonada Tradicional', 'descripcion' => 'Limonada clásica y refrescante.', 'precio' => 6.50, 'disponible' => 1, 'categoria_id' => $limonadasId, 'imagen_nombre' => 'limonada_tradicional.png']);
            Producto::create(['nombre' => 'Limonada de Mango', 'descripcion' => 'Limonada refrescante con pulpa de mango.', 'precio' => 7.00, 'disponible' => 1, 'categoria_id' => $limonadasId, 'imagen_nombre' => 'limonada_mango.png']);
            Producto::create(['nombre' => 'Limonada Cherry', 'descripcion' => 'Limonada con sabor a cereza.', 'precio' => 7.00, 'disponible' => 1, 'categoria_id' => $limonadasId, 'imagen_nombre' => 'limonada_cherry.png']);
            Producto::create(['nombre' => 'Limonada de Menta', 'descripcion' => 'Limonada con menta fresca.', 'precio' => 7.00, 'disponible' => 1, 'categoria_id' => $limonadasId, 'imagen_nombre' => 'limonada_menta.png']);
        }
        
        // ===================================================================
        // 5. BEBIDAS HELADAS (Slug: bebidas-heladas)
        // ===================================================================
        $bebidasHeladasId = $categorias['bebidas-heladas'] ?? null;
        if ($bebidasHeladasId) {
            Producto::create(['nombre' => 'Iced Americano', 'descripcion' => 'Café americano frío.', 'precio' => 8.50, 'disponible' => 1, 'categoria_id' => $bebidasHeladasId, 'imagen_nombre' => 'iced_americano.png']);
            Producto::create(['nombre' => 'Orange Americano', 'descripcion' => 'Café americano con toque de naranja.', 'precio' => 9.50, 'disponible' => 1, 'categoria_id' => $bebidasHeladasId, 'imagen_nombre' => 'orange_americano.png']);
            Producto::create(['nombre' => 'Iced Cappuccino', 'descripcion' => 'Cappuccino frío.', 'precio' => 9.50, 'disponible' => 1, 'categoria_id' => $bebidasHeladasId, 'imagen_nombre' => 'iced_cappuccino.png']);
            Producto::create(['nombre' => 'Iced Mocaccino', 'descripcion' => 'Mocaccino frío con crema batida.', 'precio' => 10.50, 'disponible' => 1, 'categoria_id' => $bebidasHeladasId, 'imagen_nombre' => 'iced_mocaccino.png']);
            Producto::create(['nombre' => 'Iced Matcha Milk', 'descripcion' => 'Matcha con leche fría.', 'precio' => 10.50, 'disponible' => 1, 'categoria_id' => $bebidasHeladasId, 'imagen_nombre' => 'iced_matcha_milk.png']);
            Producto::create(['nombre' => 'Blueberry Matcha', 'descripcion' => 'Matcha fría con arándanos.', 'precio' => 11.00, 'disponible' => 1, 'categoria_id' => $bebidasHeladasId, 'imagen_nombre' => 'blueberry_matcha.png']);
            Producto::create(['nombre' => 'Matcha y Mango', 'descripcion' => 'Matcha fría con pulpa de mango.', 'precio' => 11.00, 'disponible' => 1, 'categoria_id' => $bebidasHeladasId, 'imagen_nombre' => 'matcha_mango.png']);
            Producto::create(['nombre' => 'Rebel Sky', 'descripcion' => 'Bebida fría y colorida de fantasía.', 'precio' => 8.00, 'disponible' => 1, 'categoria_id' => $bebidasHeladasId, 'imagen_nombre' => 'rebel_sky.png']);
            Producto::create(['nombre' => 'Hawai Soda', 'descripcion' => 'Soda sabor Hawái.', 'precio' => 8.00, 'disponible' => 1, 'categoria_id' => $bebidasHeladasId, 'imagen_nombre' => 'hawai_soda.png']);
        }
        
        // ===================================================================
        // 6. FRAPPE (Slug: frappe)
        // ===================================================================
        $frappeId = $categorias['frappe'] ?? null;
        if ($frappeId) {
            Producto::create(['nombre' => 'Frappe de Oreo', 'descripcion' => 'Frappé con galletas Oreo trituradas.', 'precio' => 9.90, 'disponible' => 1, 'categoria_id' => $frappeId, 'imagen_nombre' => 'frappe_oreo.png']);
            Producto::create(['nombre' => 'Frappe de Fresa', 'descripcion' => 'Frappé refrescante de fresa.', 'precio' => 9.90, 'disponible' => 1, 'categoria_id' => $frappeId, 'imagen_nombre' => 'frappe_fresa.png']);
            Producto::create(['nombre' => 'Frappe de Chocolate', 'descripcion' => 'Frappé clásico de chocolate.', 'precio' => 9.90, 'disponible' => 1, 'categoria_id' => $frappeId, 'imagen_nombre' => 'frappe_chocolate.png']);
            Producto::create(['nombre' => 'Frappe de Capuccino', 'descripcion' => 'Frappé con sabor a capuccino.', 'precio' => 9.90, 'disponible' => 1, 'categoria_id' => $frappeId, 'imagen_nombre' => 'frappe_capuccino.png']);
            Producto::create(['nombre' => 'Frappe Chocomenta', 'descripcion' => 'Frappé de chocolate con un toque de menta.', 'precio' => 9.90, 'disponible' => 1, 'categoria_id' => $frappeId, 'imagen_nombre' => 'frappe_chocomenta.png']);
        }
        
        // ===================================================================
        // 7. COCTELES (Slug: cocteles)
        // ===================================================================
        $coctelesId = $categorias['cocteles'] ?? null;
        if ($coctelesId) {
            Producto::create(['nombre' => 'Naranja con Miel', 'descripcion' => 'Coctel de naranja con un toque de miel.', 'precio' => 11.50, 'disponible' => 1, 'categoria_id' => $coctelesId, 'imagen_nombre' => 'naranja_miel.png']);
            Producto::create(['nombre' => 'Pisco Sour', 'descripcion' => 'Coctel tradicional peruano.', 'precio' => 12.00, 'disponible' => 1, 'categoria_id' => $coctelesId, 'imagen_nombre' => 'pisco_sour.png']);
        }
        
        // ===================================================================
        // 8. POSTRES Y PASTELES (Slug: postres-y-pasteles)
        // ===================================================================
        $postresId = $categorias['postres-y-pasteles'] ?? null;
        if ($postresId) {
            Producto::create(['nombre' => 'Rollo de Canela', 'descripcion' => 'Rollo de canela horneado.', 'precio' => 7.50, 'disponible' => 1, 'categoria_id' => $postresId, 'imagen_nombre' => 'rollo_canela.png']);
            Producto::create(['nombre' => 'Torta de Zanahoria', 'descripcion' => 'Porción de torta de zanahoria con glaseado.', 'precio' => 8.00, 'disponible' => 1, 'categoria_id' => $postresId, 'imagen_nombre' => 'torta_zanahoria.png']);
            Producto::create(['nombre' => 'Torta de Chocolate', 'descripcion' => 'Porción de torta clásica de chocolate.', 'precio' => 8.00, 'disponible' => 1, 'categoria_id' => $postresId, 'imagen_nombre' => 'torta_chocolate.png']);
            Producto::create(['nombre' => 'Torta de Chocolate Helado', 'descripcion' => 'Torta de chocolate servida fría.', 'precio' => 9.50, 'disponible' => 1, 'categoria_id' => $postresId, 'imagen_nombre' => 'torta_chocolate_helado.png']);
            Producto::create(['nombre' => 'Tiramisu', 'descripcion' => 'Postre italiano a base de café y queso.', 'precio' => 9.50, 'disponible' => 1, 'categoria_id' => $postresId, 'imagen_nombre' => 'tiramisu.png']);
            Producto::create(['nombre' => 'Tocino del Cielo', 'descripcion' => 'Postre tradicional tipo flan.', 'precio' => 7.00, 'disponible' => 1, 'categoria_id' => $postresId, 'imagen_nombre' => 'tocino_cielo.png']);
            Producto::create(['nombre' => 'Pie de Limón', 'descripcion' => 'Porción de pie de limón con merengue.', 'precio' => 8.00, 'disponible' => 1, 'categoria_id' => $postresId, 'imagen_nombre' => 'pie_limon.png']);
            Producto::create(['nombre' => 'Carlota', 'descripcion' => 'Postre frío de limón o frutas.', 'precio' => 8.00, 'disponible' => 1, 'categoria_id' => $postresId, 'imagen_nombre' => 'carlota.png']);
            Producto::create(['nombre' => 'Fresa con Chocolate', 'descripcion' => 'Fresas frescas bañadas en chocolate.', 'precio' => 9.50, 'disponible' => 1, 'categoria_id' => $postresId, 'imagen_nombre' => 'fresa_chocolate.png']);
            Producto::create(['nombre' => 'Fresa con Vainilla', 'descripcion' => 'Fresas con crema batida sabor vainilla.', 'precio' => 9.50, 'disponible' => 1, 'categoria_id' => $postresId, 'imagen_nombre' => 'fresa_vainilla.png']);
            Producto::create(['nombre' => 'Fresas y Helados', 'descripcion' => 'Bowl de fresas frescas con bolas de helado.', 'precio' => 12.00, 'disponible' => 1, 'categoria_id' => $postresId, 'imagen_nombre' => 'fresas_helados.png']);
        }
        
        // ===================================================================
        // 9. WAFFLES (Slug: waffles)
        // ===================================================================
        $wafflesId = $categorias['waffles'] ?? null;
        if ($wafflesId) {
            Producto::create(['nombre' => 'Waffles con Fruta', 'descripcion' => 'Waffles frescos con frutas de temporada.', 'precio' => 11.50, 'disponible' => 1, 'categoria_id' => $wafflesId, 'imagen_nombre' => 'waffles_fruta.png']);
            Producto::create(['nombre' => 'Waffles con Helado', 'descripcion' => 'Waffles servidos con una bola de helado.', 'precio' => 11.50, 'disponible' => 1, 'categoria_id' => $wafflesId, 'imagen_nombre' => 'waffles_helado.png']);
            Producto::create(['nombre' => 'Waffles con fruta y helado', 'descripcion' => 'Waffles con fruta de temporada y helado.', 'precio' => 13.00, 'disponible' => 1, 'categoria_id' => $wafflesId, 'imagen_nombre' => 'waffles_fruta_helado.png']);
        }
        
        // ===================================================================
        // 10. SALADOS (Slug: salados)
        // ===================================================================
        $saladosId = $categorias['salados'] ?? null;
        if ($saladosId) {
            Producto::create(['nombre' => 'Empanada', 'descripcion' => 'Empanada de carne o pollo.', 'precio' => 6.00, 'disponible' => 1, 'categoria_id' => $saladosId, 'imagen_nombre' => 'empanada.png']);
            Producto::create(['nombre' => 'Pastel de Acelga', 'descripcion' => 'Porción de pastel salado de acelga.', 'precio' => 6.50, 'disponible' => 1, 'categoria_id' => $saladosId, 'imagen_nombre' => 'pastel_acelga.png']);
            Producto::create(['nombre' => 'Sandwich de Pollo', 'descripcion' => 'Sándwich de pollo desmenuzado.', 'precio' => 9.50, 'disponible' => 1, 'categoria_id' => $saladosId, 'imagen_nombre' => 'sandwich_pollo.png']);
            Producto::create(['nombre' => 'Sandwich de Jamon y Queso', 'descripcion' => 'Sándwich clásico de jamón y queso.', 'precio' => 8.50, 'disponible' => 1, 'categoria_id' => $saladosId, 'imagen_nombre' => 'sandwich_jamon_queso.png']);
        }
        
        // ===================================================================
        // 11. REBEL BUBBLES (Slug: rebel-bubbles)
        // ===================================================================
        $rebelBubblesId = $categorias['rebel-bubbles'] ?? null;
        if ($rebelBubblesId) {
            Producto::create(['nombre' => 'Green Tea', 'descripcion' => 'Té verde con bubbles.', 'precio' => 10.50, 'disponible' => 1, 'categoria_id' => $rebelBubblesId, 'imagen_nombre' => 'green_tea_bubbles.png']);
            Producto::create(['nombre' => 'Mango Tea', 'descripcion' => 'Té sabor mango con bubbles.', 'precio' => 10.50, 'disponible' => 1, 'categoria_id' => $rebelBubblesId, 'imagen_nombre' => 'mango_tea_bubbles.png']);
            Producto::create(['nombre' => 'Frutos Rojos Tea', 'descripcion' => 'Té de frutos rojos con bubbles.', 'precio' => 10.50, 'disponible' => 1, 'categoria_id' => $rebelBubblesId, 'imagen_nombre' => 'frutos_rojos_tea_bubbles.png']);
            Producto::create(['nombre' => 'Menta Tea', 'descripcion' => 'Té de menta con bubbles.', 'precio' => 10.50, 'disponible' => 1, 'categoria_id' => $rebelBubblesId, 'imagen_nombre' => 'menta_tea_bubbles.png']);
            Producto::create(['nombre' => 'Matcha Bubbles', 'descripcion' => 'Bebida de Matcha con bubbles.', 'precio' => 13.50, 'disponible' => 1, 'categoria_id' => $rebelBubblesId, 'imagen_nombre' => 'matcha_bubbles.png']);
            Producto::create(['nombre' => 'Blueberry Bubbles', 'descripcion' => 'Bebida sabor arándanos con bubbles.', 'precio' => 13.50, 'disponible' => 1, 'categoria_id' => $rebelBubblesId, 'imagen_nombre' => 'blueberry_bubbles.png']);
            Producto::create(['nombre' => 'Choco Bubbles', 'descripcion' => 'Bebida de chocolate con bubbles.', 'precio' => 13.50, 'disponible' => 1, 'categoria_id' => $rebelBubblesId, 'imagen_nombre' => 'choco_bubbles.png']);
            Producto::create(['nombre' => 'Pink Bubbles', 'descripcion' => 'Bebida rosa con sabor a frutos y bubbles.', 'precio' => 13.50, 'disponible' => 1, 'categoria_id' => $rebelBubblesId, 'imagen_nombre' => 'pink_bubbles.png']);
        }
    }
}