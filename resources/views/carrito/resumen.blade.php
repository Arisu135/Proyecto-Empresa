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
                        'brand-dark': '#4d2925',       // Marrón Oscuro
                        'brand-accent': '#ff9800',     // Naranja (Énfasis)
                        'btn-green': '#4CAF50',         // Verde para el botón de Acción
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
            <h1 class="text-2xl md:text-3xl font-bold">🛒 Resumen de tu Orden</h1>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <div class="p-6 md:p-8">
            <h2 class="text-xl font-bold text-brand-dark mb-4 border-b pb-2">Productos Añadidos:</h2>
            
            @php
                $total_calculado = 0; 
            @endphp
            
            <ul class="space-y-4 mb-6">
                @forelse ($carrito_items ?? [] as $itemKey => $item)
                    @php
                        $opciones_str = !empty($item['opciones']) 
                            ? ' (' . implode(', ', array_column($item['opciones'], 'value')) . ')'
                            : '';
                        
                        $total_calculado += $item['subtotal'];
                    @endphp
                    <li class="border-b pb-4 last:border-b-0 flex justify-between items-start">
                        <div>
                            <p class="text-lg font-semibold text-gray-800">
                                {{ $item['cantidad'] }}x {{ $item['nombre'] }}
                            </p>
                            @if ($opciones_str)
                                <p class="text-sm text-gray-500 ml-4 italic">{{ $opciones_str }}</p>
                            @endif
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-lg font-bold text-gray-800">S/. {{ number_format($item['subtotal'], 2) }}</span>
                            
                            {{-- Botones para restar/eliminar con emojis --}}
                            <div class="flex space-x-2 mt-1">
                                <a href="{{ route('carrito.restar', $itemKey) }}" class="text-sm text-brand-accent hover:text-brand-accent/70 transition">
                                    ➖ Restar 
                                </a>
                                <span class="text-sm text-gray-400">|</span>
                                <a href="{{ route('carrito.eliminar', $itemKey) }}" class="text-sm text-red-500 hover:text-red-700 transition">
                                    🗑️ Eliminar
                                </a>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="text-center text-gray-500 py-4">Tu carrito está vacío.</li>
                @endforelse
            </ul>
            
            
            <div class="flex justify-between items-center pt-4 border-t-2 border-brand-accent/50 mt-4">
                <p class="text-2xl font-extrabold text-brand-dark">TOTAL DEL PEDIDO:</p>
                <p class="text-2xl font-extrabold text-btn-green">S/. {{ number_format($total ?? $total_calculado, 2) }}</p>
            </div>
            
            @if(isset($tipoPedido))
                <p class="text-center text-md text-gray-600 mt-2">
                    Tipo de Pedido: <span class="font-semibold text-brand-dark">{{ $tipoPedido }}</span>
                </p>
            @endif
        </div>

        <div class="p-6 md:p-8 pt-0 flex flex-col space-y-3">
            
            {{-- Botón Seguir Comprando (GET) --}}
            <a href="{{ route('productos.menu') }}" class="w-full text-center py-3 bg-brand-accent text-white font-bold rounded-xl shadow-lg hover:bg-brand-accent/90 transition duration-150 text-lg">
                Seguir Comprando
            </a>

            {{-- Botón Finalizar Pedido (POST) --}}
            <form action="{{ route('pedido.finalizar') }}" method="POST">
                @csrf
                <button type="submit" @class([
                    'w-full text-center py-3 text-white font-bold rounded-xl shadow-lg transition duration-150 text-lg',
                    'bg-btn-green hover:bg-btn-green/90' => !empty($carrito_items),
                    'bg-gray-400 cursor-not-allowed' => empty($carrito_items),
                ])
                @if(empty($carrito_items)) disabled @endif>
                    Finalizar Pedido y Pagar
                </button>
            </form>

            <a href="{{ route('carrito.limpiar') }}" class="w-full text-center text-red-500 font-semibold text-sm hover:underline mt-2">
                Empezar de Nuevo (Limpiar Carrito)
            </a>

        </div>

    </div>

</body>
</html>