@extends('layouts.app')

@section('title', 'Gestión de Pedidos')

@section('content')

    <h1>Panel de Gestión de Pedidos - Rebel Jungle Café</h1>
    <p>Órdenes Recibidas por Mesa - Actualizado a las {{ now()->format('h:i:s A') }}</p>
    <hr>

    <style>
        .producto { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 8px; background-color: #f9f9f9; }
        .producto.estado-entregado { border-left: 5px solid #4CAF50; }
        .producto.estado-en-preparacion { border-left: 5px solid #FFC107; }
        .producto.estado-cancelado { border-left: 5px solid #9E9E9E; }
        .producto.estado-pendiente { border-left: 5px solid #D32F2F; }
    </style>

    @if(isset($pedidos) && $pedidos->count())
        @foreach($pedidos as $pedido)
            @php
                switch ($pedido->estado) {
                    case 'Entregado':
                        $stateClass = 'estado-entregado';
                        break;
                    case 'En Preparación':
                        $stateClass = 'estado-en-preparacion';
                        break;
                    case 'Cancelado':
                        $stateClass = 'estado-cancelado';
                        break;
                    default:
                        $stateClass = 'estado-pendiente';
                }
            @endphp

            <div class="producto {{ $stateClass }}">
                <h2 style="margin-top: 0; color: #333;">
                    Pedido #{{ $pedido->id }} - {{ $pedido->nombre_cliente ?? 'Cliente' }}
                </h2>

                <p>Total: <strong style="color: #D32F2F; font-size: 1.2em;">S/. {{ number_format($pedido->total, 2) }}</strong></p>

                <p>Notas/Instrucciones:
                    <em style="color: #666;">{{ $pedido->direccion ?: 'Ninguna' }}</em>
                </p>

                <p>Hora de Pedido: {{ optional($pedido->created_at)->format('d/m/Y h:i:s A') }}</p>

                <hr style="border-top: 1px dashed #ddd;">

                <form action="{{ route('pedido.actualizarEstado', $pedido) }}" method="POST" style="display:inline-block; margin-top: 10px;">
                    @csrf

                    <label for="estado_{{ $pedido->id }}" style="font-weight: bold;">Estado:</label>
                    <select name="estado" id="estado_{{ $pedido->id }}" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                        <option value="Pendiente" {{ $pedido->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="En Preparación" {{ $pedido->estado == 'En Preparación' ? 'selected' : '' }}>En Preparación</option>
                        <option value="Entregado" {{ $pedido->estado == 'Entregado' ? 'selected' : '' }}>Entregado</option>
                        <option value="Cancelado" {{ $pedido->estado == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>

                    <button type="submit" style="background-color: #008CBA; color: white; padding: 5px 15px; border: none; border-radius: 4px; margin-left: 10px; cursor: pointer;">Guardar</button>
                </form>
            </div>
        @endforeach
    @else
        <p>No hay pedidos en este momento.</p>
    @endif

    <a href="{{ route('admin.gestion') }}" style="display: block; margin-top: 20px; text-align: center; color: #007bff;">Volver a Gestión</a>

@endsection