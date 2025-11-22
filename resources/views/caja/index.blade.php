<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caja - Pedidos Pendientes</title>
    <link rel="stylesheet" href="{{ asset('css/caja.css') }}">
</head>
<body>
    <div class="caja-container">
        <div class="caja-header">
            <h1 class="caja-title">💰 Caja - Pedidos Pendientes</h1>
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('productos.menu', ['tipo_pedido' => 'Para Aqui']) }}" class="btn btn-green">➕ Nuevo Pedido</a>
                <a href="{{ route('mesas.index') }}" class="btn btn-gray">🍳 Cocina</a>
                <a href="{{ route('catalogo.index') }}" class="btn btn-gray">← Inicio</a>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        @if($pedidos->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">✅</div>
                <div class="empty-state-text">¡No hay pedidos pendientes de pago!</div>
            </div>
        @else
            <div class="pedidos-grid">
                @foreach ($pedidos as $pedido)
                    <div class="pedido-card">
                        <div class="pedido-header">
                            <div class="pedido-id">#{{ $pedido->id }}</div>
                            @if($pedido->numero_mesa)
                                <div class="pedido-mesa">Mesa {{ $pedido->numero_mesa }}</div>
                            @endif
                        </div>

                        <div class="pedido-cliente">{{ $pedido->nombre_cliente }}</div>

                        <ul class="pedido-detalles">
                            @foreach($pedido->detalles as $detalle)
                                <li>
                                    <strong>{{ $detalle->cantidad }}x</strong> {{ $detalle->nombre_producto }}
                                    <span style="float: right;">S/ {{ number_format($detalle->subtotal, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="pedido-total">TOTAL: S/ {{ number_format($pedido->total, 2) }}</div>

                        <div class="pedido-actions">
                            <form action="{{ route('caja.marcarPagado', $pedido) }}" method="POST" style="flex: 1;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="metodo_pago" value="efectivo">
                                <button type="submit" class="btn-pagar">💵 Efectivo</button>
                            </form>
                            
                            <form action="{{ route('caja.marcarPagado', $pedido) }}" method="POST" style="flex: 1;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="metodo_pago" value="yape">
                                <button type="submit" class="btn-pagar" style="background: #9333ea;">📱 Yape</button>
                            </form>
                            
                            <form action="{{ route('caja.marcarPagado', $pedido) }}" method="POST" style="flex: 1;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="metodo_pago" value="mixto">
                                <button type="submit" class="btn-pagar" style="background: #f59e0b;">🔄 Mixto</button>
                            </form>
                        </div>

                        <div class="pedido-actions" style="margin-top: 0.5rem;">
                            <button onclick="mostrarModalEliminar({{ $pedido->id }})" class="btn-eliminar" style="width: 100%;">
                                🗑️ Eliminar
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div id="modalEliminar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; padding:2rem; border-radius:1rem; max-width:400px; width:90%;">
            <h2 style="font-size:1.5rem; font-weight:bold; margin-bottom:1rem; color:#dc2626; text-align:center;">⚠️ Eliminar Venta</h2>
            <p style="margin-bottom:1.5rem; color:#6b7280; text-align:center;">Indica el motivo de eliminación:</p>
            
            <form id="formEliminar" method="POST" action="">
                @csrf
                @method('DELETE')
                <textarea name="motivo" rows="3" placeholder="Motivo..." required style="width:100%; padding:0.75rem; border:2px solid #d1d5db; border-radius:0.5rem; margin-bottom:1rem; font-family:inherit;"></textarea>
                
                <div style="display:flex; gap:0.75rem;">
                    <button type="button" onclick="cerrarModal()" class="btn btn-gray" style="flex:1;">Cancelar</button>
                    <button type="submit" class="btn-eliminar" style="flex:1;">Eliminar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function mostrarModalEliminar(pedidoId) {
        document.getElementById('formEliminar').action = '/caja/' + pedidoId + '/eliminar';
        document.getElementById('modalEliminar').style.display = 'flex';
    }

    function cerrarModal() {
        document.getElementById('modalEliminar').style.display = 'none';
    }
    </script>
</body>
</html>
