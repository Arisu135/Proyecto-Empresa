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
            height: 100%;
            width: 100%;
        }
        
        /* Header admin con estilos inline puros */
        .admin-header-custom {
            background-color: #166534;
            padding: 15px 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .admin-header-custom ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }
        
        .admin-header-custom li {
            margin: 0;
            padding: 0;
        }
        
        .admin-header-custom a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
            display: inline-block;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .admin-header-custom a:hover {
            color: #facc15;
            background-color: rgba(255, 255, 255, 0.1);
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
        <div class="admin-header-custom">
            <ul>
                <li><a href="{{ route('admin.panel') }}">Panel</a></li>
                <li><a href="{{ route('productos.index') }}">Productos</a></li>
                <li><a href="{{ route('admin.gestion') }}">Gestión de Pedidos</a></li>
            </ul>
        </div>
    @endunless

    {{-- Aquí se inyecta el contenido de la vista hija (index, menu, etc.) --}}
    @yield('content')
    
    @stack('scripts')
</body>
</html>
