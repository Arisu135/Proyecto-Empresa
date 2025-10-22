<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Asumo que tienes una variable $categoria_nombre pasada desde el controlador --}}
    <title>{{ $categoria_nombre ?? 'Productos' }} - Rebel Jungle Café</title> 
    
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#4d2925',       // Marrón Oscuro (del logo)
                        'brand-accent': '#ff9800',     // Naranja (Flor del logo)
                        'brand-green': '#1b5e20',      // Verde Oscuro (Hojas del logo)
                        'brand-soft-green': '#81c784',// Verde Suave (Fondo de productos)
                        'brand-red': '#E5002B',       // Rojo de alerta (para "Empezar de nuevo")
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FFFFFF; /* Blanco puro */
        }
        /* Estilo para la tarjeta de producto individual */
        .item-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(0, 0, 0, 0.05);
            min-height: 250px;
        }
        .item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(77, 41, 37, 0.1);
        }
        .btn-action {
            background-color: #4d2925;
            transition: background-color 0.2s;
        }
        .btn-action:hover {
            background-color: #6d3a35;
        }
    </style>
</head>
<body>
    <header class="bg-white sticky top-0 z-10 mb-2">
        <div class="max-w-7xl mx-auto relative px-4">
            
            <div class="flex items-center py-2">
                <img 
                    src="{{ asset('img/logo.png') }}" 
                    alt="Rebel Jungle Logo" 
                    class="h-40 w-auto object-contain" 
                    onerror="this.onerror=null; this.src='https://placehold.co/40x160/f8f8f8/4d2925?text=Logo'"
                >
            </div>

            <div class="absolute top-6 right-4 flex space-x-3">
                {{-- Botón para volver a la página de categorías (INICIO) --}}
                <a href="{{ route('catalogo.index') }}" class="px-4 py-2 text-brand-dark rounded-full hover:bg-gray-100 transition duration-150 text-sm font-semibold">
                    Volver al Menú
                </a>

                <a href="{{ route('carrito.limpiar') }}" class="btn-restart px-4 py-2 bg-brand-red text-white font-bold rounded-full shadow-lg hover:shadow-xl transition duration-150 text-sm">
                    Empezar de nuevo
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        {{-- Título dinámico de la categoría --}}
        <h2 class="text-4xl font-bold text-brand-green mb-8">
            {{ $categoria_nombre ?? 'PRODUCTOS DE LA CATEGORÍA' }}
        </h2>

        @if (session('success'))
            <div class="bg-brand-soft-green/50 border border-brand-soft-green text-brand-green px-4 py-3 rounded-xl relative mb-6 font-medium" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @php
            // **IMPORTANTE**: En un proyecto Laravel real, esta lista ($productos) 
            // vendría de tu base de datos y se pasaría a la vista desde el controlador.
            // Esto es solo un ejemplo para simular la vista de productos de "Bebidas Calientes".
            $categoria_nombre = $categoria_nombre ?? 'Bebidas Calientes'; // Si no viene, usamos un nombre por defecto
            
            $productos = [
                (object)['id' => 101, 'nombre' => 'Espresso', 'precio' => 4.50, 'imagen_nombre' => 'espresso.jpg'],
                (object)['id' => 102, 'nombre' => 'Café Americano', 'precio' => 5.50, 'imagen_nombre' => 'cafe_americano.jpg'],
                (object)['id' => 103, 'nombre' => 'Capuccino', 'precio' => 7.50, 'imagen_nombre' => 'capuccino.jpg'],
                (object)['id' => 104, 'nombre' => 'Café BomBom', 'precio' => 8.50, 'imagen_nombre' => 'cafe_bombom.jpg'],
                (object)['id' => 105, 'nombre' => 'Submarino', 'precio' => 8.00, 'imagen_nombre' => 'submarino.jpg'],
                (object)['id' => 106, 'nombre' => 'Chocolate Caliente', 'precio' => 7.50, 'imagen_nombre' => 'chocolate_caliente.jpg'],
                (object)['id' => 107, 'nombre' => 'Matcha Milk', 'precio' => 8.50, 'imagen_nombre' => 'matcha_milk.jpg'],
                (object)['id' => 108, 'nombre' => 'Leche', 'precio' => 5.00, 'imagen_nombre' => 'leche.jpg'],
            ];
        @endphp

        {{-- Grid de Productos Individuales --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">

            @foreach ($productos as $producto)
            {{-- Enlace que enviará a una ruta de detalle o un modal de opciones --}}
            <a href="{{ route('carrito.agregar', ['producto_id' => $producto->id]) }}" 
               class="item-card bg-white p-3 rounded-2xl shadow-md flex flex-col items-center justify-between text-center cursor-pointer">
                
                {{-- Contenedor de Imagen (h-36 fija el tamaño para consistencia) --}}
                <div class="h-36 w-full bg-white rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('img/productos/' . $producto->imagen_nombre) }}" 
                         alt="{{ $producto->nombre }}" 
                         class="h-full w-full object-contain">
                    {{-- NOTA: Asegúrate de que tus imágenes de productos estén en 'public/img/productos/' --}}
                </div>
                
                <div class="flex flex-col justify-end w-full flex-grow pt-2">
                    <h3 class="text-lg font-semibold text-brand-dark mb-1 leading-tight">{{ $producto->nombre }}</h3>
                    <p class="text-xl font-bold text-brand-dark">S/. {{ number_format($producto->precio, 2) }}</p>
                </div>
            </a>
            @endforeach

        </div>
    </main>

    {{-- BARRA INFERIOR DEL CARRITO (Se mantiene el código del menú principal) --}}
    @php
        // En una aplicación real, esta información se obtendría del modelo de Carrito
        $carrito = Session::get('carrito', []);
        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        $totalMonto = array_sum(array_column($carrito, 'subtotal'));
    @endphp

    @if ($totalItems > 0)
    <div class="fixed bottom-0 left-0 w-full bg-brand-dark shadow-2xl p-4 z-20">
        <div class="max-w-7xl mx-auto flex justify-between items-center text-white">
            <div class="text-lg font-semibold flex items-center space-x-2">
                <span class="text-brand-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </span>
                <span>{{ $totalItems }} Producto(s)</span>
            </div>

            <div class="flex items-center space-x-6">
                <span class="text-2xl font-extrabold">
                    Total: S/. {{ number_format($totalMonto, 2) }}
                </span>
                
                <a href="{{ route('pedido.resumen') }}" class="px-6 py-3 bg-brand-accent text-white font-bold rounded-full shadow-xl hover:bg-brand-accent/80 transition duration-150 transform hover:scale-105">
                    Ir al Resumen
                </a>
            </div>
        </div>
    </div>
    @endif
</body>
</html>