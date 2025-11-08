@extends('layouts.app')

@section('title', 'Caja - Pedidos Pendientes de Pago')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('catalogo.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            ← Volver al Inicio
        </a>
    </div>
    <h1 class="text-3xl font-bold text-center mb-2 text-gray-800">💰 Caja - Pedidos Pendientes de Pago</h1>
    <p class="text-center text-gray-500 mb-8">Actualizado a las {{ now()->format('h:i:s A') }}</p>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($pedidos->isEmpty())
        <div class="text-center py-12 bg-gray-50 rounded-lg shadow-inner">
            <p class="text-2xl text-gray-500">✅ ¡No hay pedidos pendientes de pago!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($pedidos as $pedido)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border-l-8 border-yellow-500">
                    <div class="p-5">
                        <div class="flex justify-between items-baseline mb-2">
                            <h2 class="text-2xl font-bold text-gray-800">Pedido #{{ $pedido->id }}</h2>
                            @if($pedido->numero_mesa)
                                <span class="text-lg font-bold bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                                    Mesa {{ $pedido->numero_mesa }}
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-1">
                            <strong>Cliente:</strong> {{ $pedido->nombre_cliente }}
                        </p>
                        <p class="text-gray-500 text-sm mb-3">
                            Entregado: {{ $pedido->updated_at->format('H:i') }}
                        </p>
                        
                        <ul class="mt-4 space-y-2 border-t pt-3 mb-4">
                            @foreach($pedido->detalles as $detalle)
                                <li class="flex justify-between">
                                    <span>
                                        <span class="font-bold">{{ $detalle->cantidad }}x</span> {{ $detalle->nombre_producto }}
                                    </span>
                                    <span class="text-gray-600">S/ {{ number_format($detalle->subtotal, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="border-t pt-3 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold">TOTAL:</span>
                                <span class="text-2xl font-bold text-green-600">S/ {{ number_format($pedido->total, 2) }}</span>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <form action="{{ route('caja.marcarPagado', $pedido) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition">
                                    ✓ Marcar como Pagado
                                </button>
                            </form>
                            
                            <button onclick="imprimirTicket({{ $pedido->id }}, '{{ $pedido->numero_mesa }}', '{{ $pedido->nombre_cliente }}', {{ $pedido->total }}, {{ json_encode($pedido->detalles) }})" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition">
                                🖨️
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function imprimirTicket(pedidoId, mesa, cliente, total, detalles) {
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
            <title>Ticket #${pedidoId}</title>
            <style>
                @media print {
                    @page { margin: 0; size: 80mm auto; }
                    body { margin: 0; }
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
            </style>
        </head>
        <body>
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
            </table>
            
            <div class="double-line"></div>
            
            <table>
                ${itemsHTML}
            </table>
            
            <div class="double-line"></div>
            
            <table class="total-row">
                <tr>
                    <td>TOTAL:</td>
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
        </body>
        </html>
    `);
    ventana.document.close();
    
    // Esperar a que cargue y luego imprimir
    setTimeout(() => {
        ventana.print();
    }, 250);
}
</script>
@endsection
