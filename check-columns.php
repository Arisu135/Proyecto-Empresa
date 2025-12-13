<?php
// Script para verificar columnas en producción
// Accede a: https://tu-app.onrender.com/check-columns.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('pedidos');
    
    echo "<h1>Columnas en tabla 'pedidos':</h1>";
    echo "<ul>";
    foreach ($columns as $column) {
        echo "<li>$column</li>";
    }
    echo "</ul>";
    
    echo "<h2>Columnas requeridas:</h2>";
    $required = ['tipo_pedido', 'numero_mesa', 'pagado', 'metodo_pago', 'impreso', 'eliminado'];
    foreach ($required as $col) {
        $exists = in_array($col, $columns) ? '✅' : '❌';
        echo "<p>$exists $col</p>";
    }
    
} catch (\Exception $e) {
    echo "<h1>Error:</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
