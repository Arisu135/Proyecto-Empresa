@extends('layouts.app')

@section('title', 'Mesas - Pedidos Listos para Entregar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-2 text-gray-800">🍽️ Mesas - Pedidos Listos para Entregar</h1>
    <p class="text-center text-gray-500 mb-8">Actualizado a las {{ now()->format('h:i:s A') }}</p>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($pedidos->isEmpty())
        <div class="text-center py-12 bg-gray-50 rounded-lg shadow-inner">
            <p class="text-2xl text-gray-500">🎉 ¡No hay pedidos listos para entregar!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($pedidos as $pedido)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border-l-8 border-green-500">
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
                            Listo desde: {{ $pedido->updated_at->format('H:i') }} ({{ $pedido->updated_at->diffForHumans() }})
                        </p>
                        
                        <ul class="mt-4 space-y-2 border-t pt-3 mb-4">
                            @foreach($pedido->detalles as $detalle)
                                <li>
                                    <span class="font-bold text-lg">{{ $detalle->cantidad }}x</span> {{ $detalle->nombre_producto }}
                                    @if($detalle->opciones_personalizadas && $detalle->opciones_personalizadas != '[]')
                                        <p class="text-xs text-gray-600 pl-6">
                                            @php 
                                                try {
                                                    $opciones = json_decode($detalle->opciones_personalizadas, true, 512, JSON_THROW_ON_ERROR);
                                                    if (is_array($opciones) && !empty($opciones)) {
                                                        echo '+ ' . implode(', ', array_column($opciones, 'value'));
                                                    }
                                                } catch (\JsonException $e) {
                                                    // No hacer nada si el JSON es inválido
                                                }
                                            @endphp
                                        </p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <div class="border-t pt-3 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold">Total:</span>
                                <span class="text-xl font-bold text-green-600">S/ {{ number_format($pedido->total, 2) }}</span>
                            </div>
                        </div>

                        <form action="{{ route('mesas.marcarEntregado', $pedido) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition">
                                ✓ Marcar como Entregado
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
