@extends('layouts.app')

@section('title', 'Gestión de Pedidos - Cocina')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('catalogo.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            ← Volver al Inicio
        </a>
    </div>
    <h1 class="text-3xl font-bold text-center mb-2 text-gray-800">🍳 Gestión de Pedidos de Cocina</h1>
    <p class="text-center text-gray-500 mb-8">Órdenes recibidas - Actualizado a las {{ now()->format('h:i:s A') }}</p>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(!isset($pedidos) || $pedidos->isEmpty())
        <div class="text-center py-12 bg-gray-50 rounded-lg shadow-inner">
            <p class="text-2xl text-gray-500">🎉 ¡No hay pedidos pendientes por ahora!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($pedidos as $pedido)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border-l-8 
                    @switch($pedido->estado)
                        @case('Pendiente') border-red-500 @break
                        @case('En Preparación') border-yellow-500 @break
                        @case('Listo') border-green-500 @break
                        @default border-gray-300
                    @endswitch
                ">
                    <div class="p-5">
                        <div class="flex justify-between items-baseline">
                            <h2 class="text-2xl font-bold text-gray-800">Pedido #{{ $pedido->id }}</h2>
                            <span class="text-sm font-semibold px-3 py-1 rounded-full
                                @switch($pedido->estado)
                                    @case('Pendiente') bg-red-100 text-red-800 @break
                                    @case('En Preparación') bg-yellow-100 text-yellow-800 @break
                                    @case('Listo') bg-green-100 text-green-800 @break
                                @endswitch
                            ">{{ $pedido->estado }}</span>
                        </div>
                        
                        @if($pedido->numero_mesa)
                            <p class="text-blue-600 font-bold text-lg mt-1">Mesa: {{ $pedido->numero_mesa }}</p>
                        @endif
                        <p class="text-gray-600 text-sm">Cliente: {{ $pedido->nombre_cliente }}</p>
                        <p class="text-gray-500 text-sm mt-1">Recibido: {{ $pedido->created_at->format('H:i') }} (Hace {{ $pedido->created_at->diffForHumans(null, true) }})</p>
                        
                        <ul class="mt-4 space-y-3 border-t pt-3">
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

                        <form action="{{ route('mesas.actualizarEstado', $pedido) }}" method="POST" class="mt-5">
                            @csrf
                            @method('PATCH')
                            <select name="estado" class="w-full p-2 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                                <option value="Pendiente" @if($pedido->estado == 'Pendiente') selected @endif>Pendiente</option>
                                <option value="En Preparación" @if($pedido->estado == 'En Preparación') selected @endif>En Preparación</option>
                                <option value="Listo" @if($pedido->estado == 'Listo') selected @endif>Listo para Entregar</option>
                                <option value="Entregado" @if($pedido->estado == 'Entregado') selected @endif>Entregado</option>
                                <option value="Cancelado" @if($pedido->estado == 'Cancelado') selected @endif>Cancelar</option>
                            </select>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
