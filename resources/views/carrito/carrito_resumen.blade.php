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

                <div class="flex flex-col space-y-3">
                    <button onclick="mostrarModalConfirmar()" class="w-full py-3 bg-btn-green text-white font-bold rounded-xl shadow-lg hover:bg-btn-green/90 transition duration-150 text-lg">
                        Confirmar Pedido
                    </button>
                    
                    @php
                        $ultimaCategoria = session('ultima_categoria');
                        $urlSeguirComprando = $ultimaCategoria 
                            ? route('productos.categoria', $ultimaCategoria)
                            : route('productos.menu');
                    @endphp
                    <a href="{{ $urlSeguirComprando }}" class="w-full text-center py-3 bg-brand-dark text-white font-bold rounded-xl shadow-lg hover:bg-brand-dark/90 transition duration-150 text-lg">
                        Seguir Comprando
                    </a>
                    
                    <a href="{{ route('carrito.limpiar') }}" class="w-full text-center py-3 border border-red-500 text-red-500 font-bold rounded-xl hover:bg-red-50 transition duration-150 text-lg">
                        Empezar de Nuevo
                    </a>
                </div>

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

    <!-- Modal para Confirmar Pedido -->
    <div id="modalConfirmar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; padding:30px; border-radius:12px; max-width:500px; width:90%;">
            <h2 style="font-size:24px; font-weight:bold; margin-bottom:15px; color:#4d2925;">✅ Confirmar Pedido</h2>
            <p style="margin-bottom:20px; color:#4b5563;">Por favor ingresa los siguientes datos:</p>
            
            <form action="{{ route('pedido.finalizar') }}" method="POST">
                @csrf
                
                <div style="margin-bottom:20px;">
                    <label style="display:block; font-weight:600; margin-bottom:5px; color:#374151;">Nombre del Cliente: <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nombre_cliente" placeholder="Tu nombre" required style="width:100%; padding:12px; border:2px solid #d1d5db; border-radius:8px; font-size:16px;">
                </div>
                
                <div style="display:flex; gap:10px;">
                    <button type="button" onclick="cerrarModalConfirmar()" style="flex:1; padding:12px; background:#6b7280; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer; font-size:16px;">
                        Cancelar
                    </button>
                    <button type="submit" style="flex:1; padding:12px; background:#4CAF50; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer; font-size:16px;">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function mostrarModalConfirmar() {
        document.getElementById('modalConfirmar').style.display = 'flex';
    }

    function cerrarModalConfirmar() {
        document.getElementById('modalConfirmar').style.display = 'none';
    }
    </script>

</body>
</html>