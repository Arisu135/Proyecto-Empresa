<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>Rebel Jungle - @yield('title')</title>

    {{-- *** ENLACE DE VITE (CSS y JS) *** --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- *** CLAVE: AQUÍ SE INYECTARÁ EL CSS ADICIONAL (como kiosco.css) *** --}}
    @stack('styles')

    <style>
        /* Estilos base para resetear márgenes */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%; /* Importante para vistas full-screen */
            width: 100%;
        }
    </style>
</head>

@php
    // Define si la vista actual es una vista de cliente (Kiosco)
    $isKioscoView = Request::is('/') || Request::is('menu') || Request::is('pedido/*');
    
    // Asigna una clase base para manejar estilos específicos
    $bodyClass = 'admin-body';
    if (Request::is('/')) {
        $bodyClass = 'kiosko-body';
    } elseif (Request::is('menu') || Request::is('pedido/*')) {
        // Incluye resumen, detalle, y agradecimiento en modo full-screen
        $bodyClass = 'menu-full-screen';
    }
@endphp

<body class="{{ $bodyClass }}">
    
    {{-- Navegación del Admin (Solo si NO estamos en una ruta de Kiosco) --}}
    @unless($isKioscoView)
        <header>
            <nav class="admin-nav">
                <a href="{{ route('admin.panel') }}">Panel</a>
                <a href="{{ route('productos.index') }}">Productos</a>
                <a href="{{ route('admin.gestion') }}">Gestión de Pedidos</a> 
            </nav>
        </header>
    @endunless

    {{-- Aquí se inyecta el contenido de la vista hija (index, menu, etc.) --}}
    @yield('content')
    
    @stack('scripts')
</body>
</html>