<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen del Pedido</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#4d2925',      // Marrón Oscuro
                        'brand-accent': '#ff9800',    // Naranja (Énfasis)
                        'btn-green': '#4CAF50',       // Verde para el botón de Acción
                        'soft-border': '#e5e7eb',     // Gris muy claro para bordes
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
    </style>
</head>
<body>

    <div class="max-w-xl w-full mx-auto mt-8 bg-white rounded-xl shadow-2xl overflow-hidden"> 
        
        <div class="bg-brand-dark text-white p-6 md:p-8 flex items-center justify-between">
            <h1 class="text-2xl md:text-3xl font-bold">🛒 Resumen del Pedido</h1>
        </div>

        <div class="p-6 md:p-8">
            <h2 class="text-xl font-bold text-brand-dark mb-4 border-b pb-2">Tu Pedido:</h2> 

            @if(count($carrito_items) > 0)
                <div class="space-y-4 mb-8">
                    @foreach($carrito_items as $key => $item)
                        <div class="flex justify-between items-center border-b pb-3">
                            <div class="flex-1">
                                <p class="font-bold">{{ $item['cantidad'] }}x {{ $item['nombre'] }}</p>
                                <p class="text-gray-600">S/. {{ number_format($item['precio'], 2) }} c/u</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <p class="font-bold text-brand-dark">S/. {{ number_format($item['subtotal'], 2) }}</p>
                                <div class="flex space-x-2">
                                    <a href="{{ route('carrito.restar', $key) }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">-</a>
                                    <a href="{{ route('carrito.eliminar', $key) }}" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">×</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="pt-4 mt-4">
                        <p class="text-2xl font-extrabold text-brand-dark">TOTAL A PAGAR:</p>
                        <p class="text-3xl font-extrabold text-btn-green">S/. {{ number_format($total, 2) }}</p>
                    </div>
                </div>

                <form action="{{ route('pedido.finalizar') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    @if($tipoPedido === 'Para Llevar')
                        <div class="space-y-2">
                            <label class="block text-gray-700">Nombre para el Pedido:</label>
                            <input type="text" name="nombre_cliente" class="w-full p-2 border rounded" placeholder="Tu nombre">
                        </div>
                    @endif

                    <div class="flex flex-col space-y-3">
                        <button type="submit" class="w-full py-3 bg-btn-green text-white font-bold rounded-xl shadow-lg hover:bg-btn-green/90 transition duration-150 text-lg">
                            Confirmar Pedido
                        </button>
                        
                        <a href="{{ route('productos.menu') }}" class="w-full text-center py-3 bg-brand-dark text-white font-bold rounded-xl shadow-lg hover:bg-brand-dark/90 transition duration-150 text-lg">
                            Seguir Comprando
                        </a>
                        
                        <a href="{{ route('carrito.limpiar') }}" class="w-full text-center py-3 border border-red-500 text-red-500 font-bold rounded-xl hover:bg-red-50 transition duration-150 text-lg">
                            Empezar de Nuevo
                        </a>
                    </div>
                </form>

            @else
                <div class="text-center py-8">
                    <p class="text-gray-600 mb-4">Tu carrito está vacío</p>
                    <a href="{{ route('productos.menu') }}" class="inline-block py-3 px-6 bg-brand-dark text-white font-bold rounded-xl shadow-lg hover:bg-brand-dark/90 transition duration-150">
                        Ir al Menú
                    </a>
                </div>
            @endif
        </div>
    </div>

</body>
</html>