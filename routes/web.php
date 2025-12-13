<?php

use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/menu', [CatalogoController::class, 'productos'])->name('productos.menu'); 
Route::get('/menu/categoria/{categoria_slug}', [CatalogoController::class, 'mostrarProductosPorCategoria'])->name('productos.categoria');
Route::get('/pedido/detalle/{producto}', [CatalogoController::class, 'mostrarDetalle'])->name('pedido.detalle');
Route::post('/carrito/agregar/{producto}', [CatalogoController::class, 'agregarAlCarrito'])->name('carrito.agregar');
Route::get('/carrito/restar/{itemKey}', [CatalogoController::class, 'restarDelCarrito'])->name('carrito.restar');
Route::get('/carrito/eliminar/{itemKey}', [CatalogoController::class, 'eliminarItem'])->name('carrito.eliminar'); 
Route::get('/carrito/limpiar', [CatalogoController::class, 'empezarDeNuevo'])->name('carrito.limpiar');
Route::get('/pedido/resumen', [CatalogoController::class, 'verResumen'])->name('pedido.resumen');
Route::post('/pedido/finalizar', [CatalogoController::class, 'finalizarPedido'])->name('pedido.finalizar'); 
Route::get('/pedido/{id}/confirmacion', [CatalogoController::class, 'confirmacionPedido'])->name('pedido.confirmacion');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['admin.auth'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.panel');
    })->name('admin.panel');
    Route::get('/admin/pedidos', [CatalogoController::class, 'gestion'])->name('admin.gestion');
    Route::put('/admin/pedidos/{pedido}/actualizar', [CatalogoController::class, 'actualizarEstado'])->name('pedido.actualizarEstado');
    Route::get('/admin/ventas', [CatalogoController::class, 'ventas'])->name('admin.ventas');
    Route::resource('productos', ProductoController::class);
});

Route::get('/caja', [CajaController::class, 'index'])->name('caja.index');
Route::patch('/caja/{pedido}/pagar', [CajaController::class, 'marcarPagado'])->name('caja.marcarPagado');
Route::delete('/caja/{pedido}/eliminar', [CajaController::class, 'eliminarVenta'])->name('caja.eliminarVenta');
Route::get('/caja/{pedido}/agregar-productos', [CajaController::class, 'agregarProductos'])->name('caja.agregarProductos');
Route::post('/caja/{pedido}/agregar-productos', [CajaController::class, 'agregarProductosPost'])->name('caja.agregarProductos.post');

Route::get('/mesas', [MesaController::class, 'index'])->name('mesas.index');
Route::get('/mesas/historial', [MesaController::class, 'historial'])->name('mesas.historial');
Route::patch('/mesas/{pedido}/estado', [MesaController::class, 'actualizarEstado'])->name('mesas.actualizarEstado');
Route::get('/mesas/eliminar-todo', [MesaController::class, 'eliminarTodo'])->name('mesas.eliminarTodo');

Route::get('/admin/ventas-eliminadas', [CatalogoController::class, 'ventasEliminadas'])->name('admin.ventas.eliminadas');
Route::get('/admin/ventas-eliminadas/cocina', [CatalogoController::class, 'ventasEliminadasCocina'])->name('admin.ventas.eliminadas.cocina');
Route::get('/admin/ventas-eliminadas/limpiar/{tipo}', [CatalogoController::class, 'limpiarHistorialVentas'])->name('admin.ventas.eliminadas.limpiar');
Route::delete('/admin/ventas/{pedido}/eliminar', [CatalogoController::class, 'eliminarVentaAdmin'])->name('admin.ventas.eliminar');

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

Route::get('/ops/check-db', function () {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('pedidos');
    return response()->json([
        'columns' => $columns,
        'has_pagado' => in_array('pagado', $columns),
        'has_metodo_pago' => in_array('metodo_pago', $columns),
        'has_eliminado' => in_array('eliminado', $columns),
    ]);
});

Route::get('/ops/check-detalles', function () {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('pedido_detalles');
    return response()->json([
        'columns' => $columns,
        'has_opciones' => in_array('opciones_personalizadas', $columns),
    ]);
});

Route::get('/ops/logs', function () {
    $logFile = storage_path('logs/laravel.log');
    if (!file_exists($logFile)) {
        return response()->json(['error' => 'No log file found']);
    }
    $lines = file($logFile);
    $lastLines = array_slice($lines, -100);
    return response('<pre>' . implode('', $lastLines) . '</pre>');
});

Route::get('/api/print/pending', [\App\Http\Controllers\Api\PrintController::class, 'getPendingPrints']);
Route::get('/tickets/{pedido}/cocina', [CatalogoController::class, 'ticketCocina'])->name('tickets.cocina');
Route::get('/tickets/{pedido}/caja', [CatalogoController::class, 'ticketCaja'])->name('tickets.caja');
