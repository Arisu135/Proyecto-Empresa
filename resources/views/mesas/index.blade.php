<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cocina - Pedidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#4d2925',
                        'brand-accent': '#ff9800',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FFFFFF; }
        .pedido-card { transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; }
        .pedido-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(77, 41, 37, 0.15); }
    </style>
</head>
<body>
    <header class="bg-white sticky top-0 z-10 mb-2 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div></div>
                <h1 class="text-3xl font-bold text-brand-dark">🍳 COCINA</h1>
                <a href="{{ route('productos.menu', ['tipo_pedido' => 'Para Aqui']) }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg">
                    ← Volver
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-4 px-4">
        @if(session('pedido_confirmado'))
            <div id="mensajeConfirmacion" class="bg-green-50 border-2 border-green-500 rounded-xl p-6 mb-6 shadow-lg">
                <h2 class="text-2xl font-bold text-green-700 mb-4 text-center">✅ ¡Gracias! Tu pedido ha sido enviado a cocina.</h2>
                
                <div class="bg-white rounded-lg p-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Detalles del Pedido:</h3>
                    <div class="space-y-2 text-sm">
                        <p><strong>Número de Pedido:</strong> <span class="text-green-600 font-bold text-xl">#{{ session('pedido_id') }}</span></p>
                        <p><strong>Cliente:</strong> {{ session('pedido_cliente') }}</p>
                        <p><strong>Fecha y Hora:</strong> {{ session('pedido_fecha') }}</p>
                        <p><strong>Estado:</strong> <span class="text-red-600 font-bold">Pendiente</span></p>
                    </div>
                    
                    <div class="border-t mt-3 pt-3">
                        <p class="text-lg font-bold text-gray-800">TOTAL PAGADO:</p>
                        <p class="text-2xl font-bold text-green-600">S/. {{ number_format(session('pedido_total'), 2) }}</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-4">
                    <h4 class="font-bold text-gray-700 mb-2">Productos Incluidos:</h4>
                    <ul class="space-y-1 text-sm">
                        @foreach(session('pedido_detalles') as $detalle)
                            <li>{{ $detalle->cantidad }}x {{ $detalle->nombre_producto }} - S/. {{ number_format($detalle->subtotal, 2) }}</li>
                        @endforeach
                    </ul>
                </div>
                
                <button onclick="cerrarMensaje()" class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                    Cerrar
                </button>
            </div>
            <script>
                setTimeout(function() {
                    var mensaje = document.getElementById('mensajeConfirmacion');
                    if (mensaje) {
                        mensaje.style.display = 'none';
                    }
                }, 10000); // Se cierra automáticamente después de 10 segundos
                
                function cerrarMensaje() {
                    document.getElementById('mensajeConfirmacion').style.display = 'none';
                }
            </script>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(!isset($pedidos) || $pedidos->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <p class="text-2xl text-gray-500">🎉 ¡No hay pedidos pendientes!</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @foreach ($pedidos as $pedido)
                    @php
                        $minutos = $pedido->created_at->diffInMinutes(now());
                        $cantidadProductos = $pedido->detalles->count();
                        $esMuchosProductos = $cantidadProductos > 3;
                        
                        if ($esMuchosProductos) {
                            $colorBorde = $minutos >= 10 ? 'border-red-500' : ($minutos >= 5 ? 'border-yellow-500' : 'border-green-500');
                            $colorFondo = $minutos >= 10 ? 'bg-red-50' : ($minutos >= 5 ? 'bg-yellow-50' : 'bg-green-50');
                        } else {
                            $colorBorde = $minutos >= 10 ? 'border-red-500' : ($minutos >= 5 ? 'border-yellow-500' : 'border-green-500');
                            $colorFondo = $minutos >= 10 ? 'bg-red-50' : ($minutos >= 5 ? 'bg-yellow-50' : 'bg-green-50');
                        }
                    @endphp
                    <div class="pedido-card {{ $colorFondo }} p-4 rounded-2xl shadow-md flex flex-col border-4 {{ $colorBorde }}" onclick="abrirModal({{ $pedido->id }}, '{{ $pedido->nombre_cliente }}', {{ $minutos }})">
                        <div class="text-center mb-3">
                            <h3 class="text-xl font-bold text-brand-dark">#{{ $pedido->id }}</h3>
                            <p class="text-sm text-gray-600">{{ $pedido->nombre_cliente }}</p>
                            @if($pedido->numero_mesa)
                                <p class="text-blue-600 font-bold">Mesa {{ $pedido->numero_mesa }}</p>
                            @endif
                        </div>

                        <ul class="text-xs space-y-1 mb-3 flex-grow">
                            @foreach($pedido->detalles as $detalle)
                                <li class="border-b pb-1">
                                    <span class="font-bold">{{ $detalle->cantidad }}x</span> {{ $detalle->nombre_producto }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <!-- Modal -->
    <div id="modalPedido" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; padding:30px; border-radius:12px; max-width:400px; width:90%;">
            <h2 id="modalTitulo" style="font-size:24px; font-weight:bold; margin-bottom:10px; color:#4d2925; text-align:center;"></h2>
            <p id="modalInfo" style="margin-bottom:20px; color:#6b7280; text-align:center;"></p>
            
            <div style="display:flex; flex-direction:column; gap:15px;">
                <form id="formListo" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="estado" value="Listo">
                    <button type="submit" style="width:100%; padding:15px; background:#10b981; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer; font-size:18px;">
                        ✅ Marcar como Listo
                    </button>
                </form>
                
                <button onclick="mostrarConfirmacionEliminar()" style="width:100%; padding:15px; background:#ef4444; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer; font-size:18px;">
                    🗑️ Eliminar Pedido
                </button>
                
                <button onclick="cerrarModal()" style="width:100%; padding:12px; background:#6b7280; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Confirmación Eliminar -->
    <div id="modalEliminar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:10000; align-items:center; justify-content:center;">
        <div style="background:white; padding:30px; border-radius:12px; max-width:400px; width:90%;">
            <h2 style="font-size:20px; font-weight:bold; margin-bottom:15px; color:#ef4444; text-align:center;">⚠️ Eliminar Pedido</h2>
            <p style="margin-bottom:20px; color:#4b5563; text-align:center;">Indica el motivo de eliminación:</p>
            
            <form id="formEliminar" method="POST" action="">
                @csrf
                @method('PATCH')
                <input type="hidden" name="estado" value="Cancelado">
                <textarea name="motivo_cancelacion" rows="3" placeholder="Motivo..." required style="width:100%; padding:10px; border:2px solid #d1d5db; border-radius:8px; margin-bottom:15px;"></textarea>
                
                <div style="display:flex; gap:10px;">
                    <button type="button" onclick="cerrarModalEliminar()" style="flex:1; padding:12px; background:#6b7280; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">
                        Cancelar
                    </button>
                    <button type="submit" style="flex:1; padding:12px; background:#ef4444; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    let pedidoIdActual = null;

    function abrirModal(id, cliente, minutos) {
        pedidoIdActual = id;
        document.getElementById('modalTitulo').textContent = 'Pedido #' + id;
        document.getElementById('modalInfo').textContent = cliente;
        document.getElementById('formListo').action = '/mesas/' + id + '/estado';
        document.getElementById('formEliminar').action = '/mesas/' + id + '/estado';
        document.getElementById('modalPedido').style.display = 'flex';
    }

    function cerrarModal() {
        document.getElementById('modalPedido').style.display = 'none';
    }

    function mostrarConfirmacionEliminar() {
        document.getElementById('modalEliminar').style.display = 'flex';
    }

    function cerrarModalEliminar() {
        document.getElementById('modalEliminar').style.display = 'none';
    }


    </script>
</body>
</html>
