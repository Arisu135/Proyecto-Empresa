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
                            
                            <button onclick="imprimirTicket({{ $pedido->id }})" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition">
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
function imprimirTicket(pedidoId) {
    const pedidoCard = event.target.closest('.bg-white');
    const contenido = pedidoCard.innerHTML;
    
    const ventana = window.open('', '_blank', 'width=300,height=600');
    ventana.document.write(`
        <html>
        <head>
            <title>Ticket - Pedido #${pedidoId}</title>
            <style>
                body { font-family: monospace; font-size: 12px; margin: 10px; }
                h2 { text-align: center; margin: 5px 0; }
                .total { font-size: 16px; font-weight: bold; margin-top: 10px; border-top: 2px solid #000; padding-top: 5px; }
            </style>
        </head>
        <body onload="window.print(); window.close();">
            ${contenido}
        </body>
        </html>
    `);
    ventana.document.close();
}
</script>
@endsection
