<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // 🛑 AÑADE ESTAS RUTAS PARA LA PRUEBA DIAGNÓSTICA DEL 505 🛑
        '/carrito/agregar/*',
        '/pedido/resumen', // Si es una ruta POST, inclúyela
        '/pedido/finalizar', // Si es una ruta POST, inclúyela
        // Puedes usar comodines para cubrir todas las rutas del carrito
        '/carrito/*'
    ];
}