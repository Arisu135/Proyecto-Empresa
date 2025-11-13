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
                <a href="{{ route('catalogo.index') }}" class="btn btn-gray">← Volver al Inicio</a>
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

    @if(session('imprimir_ticket'))
        @php
            $pedidoImprimir = \App\Models\Pedido::with('detalles')->find(session('imprimir_ticket'));
        @endphp
        @if($pedidoImprimir)
            <script>
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        imprimirTicketPago(
                            {{ $pedidoImprimir->id }},
                            '{{ $pedidoImprimir->numero_mesa ?? "" }}',
                            '{{ $pedidoImprimir->nombre_cliente }}',
                            {{ $pedidoImprimir->total }},
                            '{{ ucfirst($pedidoImprimir->metodo_pago) }}',
                            @json($pedidoImprimir->detalles)
                        );
                    }, 300);
                });
            </script>
        @endif
    @endif

    <script>
    function imprimirTicketPago(pedidoId, mesa, cliente, total, metodoPago, detalles) {
        const fecha = new Date().toLocaleString('es-PE', { 
            day: '2-digit', 
            month: '2-digit', 
            year: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        let itemsHTML = '';
        detalles.forEach(item => {
            itemsHTML += `
                <tr>
                    <td>${item.cantidad}x</td>
                    <td>${item.nombre_producto}</td>
                    <td style="text-align: right;">S/ ${parseFloat(item.subtotal).toFixed(2)}</td>
                </tr>
            `;
        });
        
        const ventana = window.open('', '_blank', 'width=300,height=600');
        ventana.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Ticket #${pedidoId}</title>
                <style>
                    @media print {
                        @page { margin: 0; size: 80mm auto; }
                        body { margin: 0; }
                        .btn-imprimir { display: none !important; }
                    }
                    body {
                        font-family: 'Courier New', monospace;
                        font-size: 12px;
                        width: 80mm;
                        margin: 0 auto;
                        padding: 5mm;
                    }
                    .center { text-align: center; }
                    .bold { font-weight: bold; }
                    .line { border-top: 1px dashed #000; margin: 5px 0; }
                    .double-line { border-top: 2px solid #000; margin: 5px 0; }
                    table { width: 100%; border-collapse: collapse; }
                    td { padding: 2px 0; }
                    .total-row { font-size: 14px; font-weight: bold; }
                    .btn-imprimir {
                        display: block;
                        width: 100%;
                        padding: 15px;
                        margin: 20px 0;
                        background: #10b981;
                        color: white;
                        border: none;
                        border-radius: 8px;
                        font-size: 18px;
                        font-weight: bold;
                        cursor: pointer;
                        font-family: Arial, sans-serif;
                    }
                    .btn-imprimir:active {
                        background: #059669;
                    }
                </style>
            </head>
            <body>
                <button class="btn-imprimir" onclick="window.print()">🖨️ IMPRIMIR TICKET</button>
                
                <div class="center bold" style="font-size: 16px; margin-bottom: 5px;">
                    REBEL JUNGLE CAFE
                </div>
                <div class="center" style="font-size: 10px; margin-bottom: 10px;">
                    Kiosco Digital
                </div>
                <div class="line"></div>
                
                <table>
                    <tr>
                        <td class="bold">Pedido:</td>
                        <td style="text-align: right;">#${pedidoId}</td>
                    </tr>
                    ${mesa ? `<tr><td class="bold">Mesa:</td><td style="text-align: right;">${mesa}</td></tr>` : ''}
                    <tr>
                        <td class="bold">Cliente:</td>
                        <td style="text-align: right;">${cliente}</td>
                    </tr>
                    <tr>
                        <td class="bold">Fecha:</td>
                        <td style="text-align: right;">${fecha}</td>
                    </tr>
                    <tr>
                        <td class="bold">Método Pago:</td>
                        <td style="text-align: right;">${metodoPago}</td>
                    </tr>
                </table>
                
                <div class="double-line"></div>
                
                <table>
                    ${itemsHTML}
                </table>
                
                <div class="double-line"></div>
                
                <table class="total-row">
                    <tr>
                        <td>TOTAL PAGADO:</td>
                        <td style="text-align: right;">S/ ${parseFloat(total).toFixed(2)}</td>
                    </tr>
                </table>
                
                <div class="line"></div>
                <div class="center" style="margin-top: 10px; font-size: 10px;">
                    ¡Gracias por su compra!
                </div>
                <div class="center" style="font-size: 10px;">
                    @rebel_jungle_cafe
                </div>
                
                <button class="btn-imprimir" onclick="window.print()">🖨️ IMPRIMIR TICKET</button>
            </body>
            </html>
        `);
        ventana.document.close();
        
        // Imprimir INMEDIATAMENTE
        setTimeout(() => {
            ventana.print();
            // Cerrar ventana después de imprimir
            setTimeout(() => {
                ventana.close();
            }, 1000);
        }, 100);
    }

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
