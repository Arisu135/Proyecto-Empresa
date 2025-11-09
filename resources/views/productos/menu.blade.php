<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú - Rebel Jungle Café</title>
    
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#4d2925',       // Marrón Oscuro (del logo)
                        'brand-accent': '#ff9800',     // Naranja (Flor del logo)
                        'brand-green': '#1b5e20',      // Verde Oscuro (Hojas del logo)
                        'brand-soft-green': '#81c784', // Verde Suave (Fondo de productos)
                        'brand-red': '#E5002B',        // Rojo de alerta (para "Empezar de nuevo")
                    },
                    fontFamily: {
                        // Usamos Inter como base.
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
        /* La tarjeta ahora es el único elemento interactivo, con mejor feedback visual */
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(0, 0, 0, 0.05);
            padding-bottom: 1rem; 
            min-height: 250px; /* Asegura una altura mínima uniforme */
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(77, 41, 37, 0.15); /* Sombra más fuerte al pasar el mouse */
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
                <a href="{{ route('carrito.limpiar') }}" class="btn-restart px-4 py-2 bg-brand-red text-white font-bold rounded-full shadow-lg hover:shadow-xl transition duration-150 text-sm">
                    Empezar de nuevo
                </a>
            </div>
            
            <!-- Botones de Caja, Cocina y Ventas -->
            <div class="absolute top-20 right-4 flex flex-col gap-2">
                <a href="{{ route('caja.index') }}" class="px-4 py-2 bg-green-600 text-white font-bold rounded-lg shadow-lg hover:bg-green-700 transition text-sm text-center">
                    💰 Caja
                </a>
                <a href="{{ route('mesas.index') }}" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg shadow-lg hover:bg-blue-700 transition text-sm text-center">
                    🍳 Cocina
                </a>
                <a href="{{ route('admin.ventas') }}" class="px-4 py-2 bg-purple-600 text-white font-bold rounded-lg shadow-lg hover:bg-purple-700 transition text-sm text-center">
                    📊 Ventas
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-brand-dark mb-8">
            INICIO
        </h2>

        @if (session('success'))
            <div class="bg-brand-soft-green/50 border border-brand-soft-green text-brand-green px-4 py-3 rounded-xl relative mb-6 font-medium" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- INICIO DEL GRID DE CATEGORÍAS --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">

            {{-- 🛑 CORRECCIÓN CLAVE: Iteramos sobre $categorias que vienen de la DB, no un array estático. --}}
            {{-- También se corrige la sintaxis del route() para asegurar que el slug se pase correctamente. --}}
            @foreach ($categorias as $categoria)
            
            <a href="{{ route('productos.categoria', ['categoria_slug' => $categoria->slug]) }}" 
               class="product-card bg-white p-4 rounded-2xl shadow-md flex flex-col justify-center items-center cursor-pointer transform hover:-translate-y-1 transition-transform duration-300">
                
                <div class="h-36 bg-white rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('img/categorias/' . $categoria->imagen_nombre) }}" 
                        alt="{{ $categoria->nombre }}" 
                        class="h-full w-full object-contain"
                        onerror="this.onerror=null; this.src='https://placehold.co/150x150/f8f8f8/4d2925?text=IMG'">
                </div>
                
                <div class="flex flex-col justify-between flex-grow">
                    <h3 class="text-xl font-bold text-brand-dark mb-1 leading-tight text-center">{{ $categoria->nombre }}</h3>
                </div>
            </a>
            @endforeach

        </div>
    </main>

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