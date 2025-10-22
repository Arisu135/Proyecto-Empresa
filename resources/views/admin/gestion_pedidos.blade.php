@extends('layouts.app')

@section('title', 'Gestión de Pedidos')

@section('content')

    <h1>Panel de Gestión de Pedidos - Rebel Jungle Café</h1>
    <p>Órdenes Recibidas por Mesa - Actualizado a las **{{ now()->format('h:i:s A') }}**</p>
    <hr>
    
    <!-- Enlace para actualizar la vista -->
    <a href="/pedidos" style="background-color: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 20px;">Actualizar Lista</a>
    
    @if($pedidos->isEmpty())
        <div style="padding: 20px; background-color: #ffe0b2; border: 1px solid #ff9800; border-radius: 6px;">
            <p style="font-size: 1.1em;">No hay pedidos pendientes en este momento. ¡A esperar el próximo cliente!</p>
        </div>
    @else
        <!-- Bucle para recorrer y mostrar cada pedido -->
        @foreach ($pedidos as $pedido)
            <div class="producto" 
                style="
                    border: 1px solid #ccc;
                    padding: 15px;
                    margin-bottom: 15px;
                    border-radius: 8px;
                    background-color: #f9f9f9;
                    border-left: 5px solid 
                    @if($pedido->estado == 'Entregado') #4CAF50 /* Verde */
                    @elseif($pedido->estado == 'En Preparación') #FFC107 /* Amarillo */
                    @elseif($pedido->estado == 'Cancelado') #9E9E9E /* Gris */
                    @else #D32F2F /* Rojo (Pendiente) */
                    @endif;">
                
                <h2 style="margin-top: 0; color: #333;">
                    Pedido #{{ $pedido->id }} - **{{ $pedido->nombre_cliente }}**
                </h2>
                
                <p>Total: <strong style="color: #D32F2F; font-size: 1.2em;">S/. {{ number_format($pedido->total, 2) }}</strong></p>
                
                <p>Notas/Instrucciones: 
                    <em style="color: #666;">{{ $pedido->direccion ?: 'Ninguna' }}</em>
                </p>
                
                <p>Hora de Pedido: {{ $pedido->created_at->format('d/m/Y h:i:s A') }}</p>
                
                <hr style="border-top: 1px dashed #ddd;">

                <!-- Formulario para Cambiar Estado -->
                <form action="{{ route('pedidos.actualizar', $pedido) }}" method="POST" style="display:inline-block; margin-top: 10px;">
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
    @endif
    
    <a href="/productos" style="display: block; margin-top: 20px; text-align: center; color: #007bff;">Volver a Gestión de Productos</a>

@endsection