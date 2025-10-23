<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Título dinámico. Se corrige para usar $categoria->nombre, protegido contra NULL --}}
    <title>{{ $categoria->nombre ?? 'Productos de Categoría' }} - Rebel Jungle Café</title> 
    
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
                        'brand-soft-green': '#81c784', // Verde Suave (Fondo de productos)
                        'brand-red': '#E5002B',        // Rojo de alerta
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Estilo para la tarjeta de producto individual */
        .item-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(77, 41, 37, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    {{-- EL HEADER (BARRA SUPERIOR) --}}
<header class="bg-brand-dark sticky top-0 z-10 shadow-lg">
    <div class="max-w-7xl mx-auto flex justify-between items-center py-4 px-4 sm:px-6 lg:px-8">
        {{-- Botón Atrás: Vuelve al menú de categorías --}}
        <a href="{{ route('productos.menu') }}" class="flex items-center text-white hover:text-brand-accent transition duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="font-semibold text-lg">Atrás</span>
        </a>
        
        {{-- Título Central (Nombre de la Categoría) --}}
        <h1 class="text-xl font-bold text-white uppercase tracking-wider">
            {{ $categoria->nombre ?? 'PRODUCTOS' }}
        </h1>

        {{-- Botón Reiniciar/Empezar de Nuevo (limpia el carrito) --}}
        <a href="{{ route('carrito.limpiar') }}" class="px-4 py-2 bg-brand-red text-white font-bold rounded-md shadow-lg hover:shadow-xl transition duration-150 text-sm">
            Reiniciar
        </a>
    </div>
</header>

    {{-- EL CUERPO PRINCIPAL CON EL GRID DE PRODUCTOS --}}
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Mensajes de Session --}}
        @if (session('success'))
            <div class="bg-brand-soft-green/50 border border-brand-soft-green text-brand-green px-4 py-3 rounded-xl relative mb-6 font-medium" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        {{-- Grid de Productos Individuales --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">

            {{-- Protección: Si $productos está vacío, muestra un mensaje --}}
            @forelse ($productos as $producto)
            {{-- Tarjeta del Producto Individual, al hacer clic lleva al detalle/personalización --}}
            {{-- CORREGIDO: Se usa el slug o ID, y se protege con ?? --}}
            <a href="{{ route('pedido.detalle', ['producto' => $producto->slug ?? $producto->id]) }}" 
               class="item-card bg-white p-3 rounded-2xl shadow-md flex flex-col items-center justify-between text-center cursor-pointer">
                
                {{-- Contenedor de Imagen --}}
                <div class="w-full h-36 bg-white rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                    {{-- PROTECCIÓN: Usa el operador ?? para prevenir un error si imagen_nombre es NULL --}}
                    @php
                        $imagenNombre = $producto->imagen_nombre ?? 'default.png';
                    @endphp
                    <img src="{{ asset('img/productos/' . $imagenNombre) }}" 
                          alt="{{ $producto->nombre ?? 'Producto' }}" 
                          class="h-full w-full object-contain"
                          onerror="this.onerror=null; this.src='https://placehold.co/150x150/f8f8f8/4d2925?text=IMG'">
                </div>
                
                {{-- Nombre y Precio --}}
                <div class="flex flex-col justify-end w-full flex-grow pt-2">
                    {{-- PROTECCIÓN: Se protege el nombre y el precio --}}
                    <h3 class="text-lg font-semibold text-brand-dark mb-1 leading-tight">{{ $producto->nombre ?? 'Sin nombre' }}</h3>
                    <p class="text-xl font-bold text-brand-accent">S/. {{ number_format($producto->precio ?? 0, 2) }}</p>
                </div>
            </a>
            @empty
                {{-- Contenido que se muestra si no hay productos --}}
                <div class="col-span-full text-center py-10 bg-white rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold text-gray-700 mb-2">¡Ups! Categoría vacía 😔</h2>
                    <p class="text-gray-500">Aún no hemos agregado productos a la categoría **{{ $categoria->nombre ?? 'Desconocida' }}**.</p>
                    <div class="mt-6">
                        <a href="{{ route('productos.menu') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-brand-dark hover:bg-brand-accent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-accent">
                            ← Volver al Menú Principal
                        </a>
                    </div>
                </div>
            @endforelse

        </div>
    </main>

    {{-- BARRA INFERIOR DEL CARRITO --}}
    @php
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