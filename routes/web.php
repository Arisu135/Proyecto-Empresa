<?php

use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas de Kiosko (Frontend)
|--------------------------------------------------------------------------
| Estas rutas gestionan el flujo de pedido de los clientes.
*/

// Inicio del Kiosko (Ruta principal '/')
Route::get('/', [CatalogoController::class, 'index'])->name('catalogo.index');

// 1. Menú de Productos (Paso 1: Categorías)
Route::get('/menu', [CatalogoController::class, 'productos'])->name('productos.menu'); 

// 2. Vista de Productos dentro de una Categoría
Route::get('/menu/categoria/{categoria_slug}', [CatalogoController::class, 'mostrarProductosPorCategoria'])->name('productos.categoria');


// 3. Detalle y Personalización del Producto (Paso 2: Opciones)
Route::get('/pedido/detalle/{producto}', [CatalogoController::class, 'mostrarDetalle'])->name('pedido.detalle');

// Carrito de Compras
Route::post('/carrito/agregar/{producto}', [CatalogoController::class, 'agregarAlCarrito'])->name('carrito.agregar');
Route::get('/carrito/restar/{itemKey}', [CatalogoController::class, 'restarDelCarrito'])->name('carrito.restar');

// Rutas de Eliminación/Limpieza (Simplificado para Kiosko: usa GET)
Route::get('/carrito/eliminar/{itemKey}', [CatalogoController::class, 'eliminarItem'])->name('carrito.eliminar'); 
Route::get('/carrito/limpiar', [CatalogoController::class, 'empezarDeNuevo'])->name('carrito.limpiar');

// Proceso de Pedido
Route::get('/pedido/resumen', [CatalogoController::class, 'verResumen'])->name('pedido.resumen');

// --- FLUJO CLAVE DE PEDIDO ---
// 1. Guarda la Orden en DB y redirige.
Route::post('/pedido/finalizar', [CatalogoController::class, 'finalizarPedido'])->name('pedido.finalizar'); 

// 2. Muestra la Confirmación del pedido específico (ruta dinámica)
Route::get('/pedido/{id}/confirmacion', [CatalogoController::class, 'confirmacionPedido'])->name('pedido.confirmacion');
// -----------------------------


/*
|--------------------------------------------------------------------------
| Rutas de Administración (Backend)
|--------------------------------------------------------------------------
| Estas rutas gestionan el panel de control, productos y pedidos para el personal.
*/

// Login de Admin
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Rutas protegidas de Admin
Route::middleware(['admin.auth'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.panel');
    })->name('admin.panel');
    
    Route::get('/admin/pedidos', [CatalogoController::class, 'gestion'])->name('admin.gestion');
    Route::put('/admin/pedidos/{pedido}/actualizar', [CatalogoController::class, 'actualizarEstado'])->name('pedido.actualizarEstado');
    Route::get('/admin/ventas', [CatalogoController::class, 'ventas'])->name('admin.ventas');
    Route::resource('productos', ProductoController::class);
});

// Vista de Caja
Route::get('/caja', [CajaController::class, 'index'])->name('caja.index');
Route::patch('/caja/{pedido}/pagar', [CajaController::class, 'marcarPagado'])->name('caja.marcarPagado');
Route::delete('/caja/{pedido}/eliminar', [CajaController::class, 'eliminarVenta'])->name('caja.eliminarVenta');

// Vista de Mesas (ahora es Cocina completa)
Route::get('/mesas', [MesaController::class, 'index'])->name('mesas.index');
Route::patch('/mesas/{pedido}/estado', [MesaController::class, 'actualizarEstado'])->name('mesas.actualizarEstado');
Route::get('/mesas/eliminar-todo', [MesaController::class, 'eliminarTodo'])->name('mesas.eliminarTodo');

// Historial de ventas eliminadas
Route::get('/admin/ventas-eliminadas', [CatalogoController::class, 'ventasEliminadas'])->name('admin.ventas.eliminadas');
Route::delete('/admin/ventas/{pedido}/eliminar', [CatalogoController::class, 'eliminarVentaAdmin'])->name('admin.ventas.eliminar');



// Temporary ops route: clear caches/view in environments where you cannot run artisan directly.
// PROTECTION: set ADMIN_CLEAR_TOKEN in your environment (Render Dashboard) and call
// /ops/clear-cache?token=YOUR_TOKEN once. Remove this route after use.
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
Route::get('/ops/clear-cache', function () {
    $provided = request()->query('token');
    $secret = env('ADMIN_CLEAR_TOKEN');
    if (!$secret || !$provided || !hash_equals($secret, $provided)) {
        abort(403, 'Unauthorized');
    }

    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    return response()->json([
        'view' => trim(Artisan::output()),
        'status' => 'ok',
    ]);
});

Route::get('/ops/check-db', function () {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('pedidos');
    return response()->json([
        'columns' => $columns,
        'has_pagado' => in_array('pagado', $columns),
        'has_metodo_pago' => in_array('metodo_pago', $columns),
        'has_eliminado' => in_array('eliminado', $columns),
    ]);
});