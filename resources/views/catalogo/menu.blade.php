@extends('layouts.app') 
@php $isKiosko = true; @endphp 

@section('title', 'Menú - Rebel Jungle Café')

@push('styles')
    {{-- Carga el CSS centralizado para el Kiosco --}}
    <link rel="stylesheet" href="{{ asset('css/kiosco.css') }}">
@endpush

@section('content')

{{-- HEADER: Botones y Título --}}
<div class="menu-header">
    <div class="header-buttons">
        <a href="{{ route('catalogo.index') }}" class="btn-inicio">< Volver al Inicio</a>
        <a href="{{ route('carrito.limpiar') }}" class="btn-empezar-nuevo">Empezar de nuevo</a>
    </div>
    <h1>Nuestro Menú - Rebel Jungle Café</h1>
    <p>Tipo de Pedido: **{{ Session::get('tipo_pedido', 'No Seleccionado') }}**</p>
    
    {{-- Mensajes de Éxito/Error/Advertencia --}}
    @if(Session::has('success'))
        <div class="alerta-success">{{ Session::get('success') }}</div>
    @endif
    @if(Session::has('error'))
        <div class="alerta-error">{{ Session::get('error') }}</div>
    @endif
</div>

{{-- GRID DE PRODUCTOS --}}
<div class="menu-grid">
    @forelse ($productos as $producto)
        <a href="{{ route('pedido.detalle', $producto->id) }}" class="producto-card">
            
            @if ($producto->imagen_url)
                {{-- Muestra la imagen si existe --}}
                <img src="{{ asset('storage/' . $producto->imagen_url) }}" alt="{{ $producto->nombre }}" class="img-placeholder">
            @else
                {{-- Muestra un placeholder si no hay imagen --}}
                <div class="img-placeholder">
                    {{ $producto->nombre }}
                </div>
            @endif

            <div class="card-content">
                <h3>{{ $producto->nombre }}</h3>
                
                {{-- Puedes incluir una descripción breve si el modelo lo tiene --}}
                {{-- <p class="descripcion">{{ Str::limit($producto->descripcion, 50) }}</p> --}}
                
                <p class="precio">S/. {{ number_format($producto->precio, 2) }}</p>
            </div>
        </a>
    @empty
        <p class="no-productos-msg">No hay productos disponibles en este momento. Vuelve pronto.</p>
    @endforelse
</div>

{{-- FOOTER FIJO: Resumen del Pedido --}}
@php
    $carrito = Session::get('carrito', []);
    $totalItems = array_sum(array_column($carrito, 'cantidad'));
    $totalMonto = array_sum(array_column($carrito, 'subtotal'));
@endphp

@if ($totalItems > 0)
    <div class="menu-footer">
        <div class="total-carrito">
            {{ $totalItems }} productos | Total: S/. {{ number_format($totalMonto, 2) }}
        </div>
        
        <a href="{{ route('pedido.resumen') }}" class="btn-footer btn-ver-pedido">
            VER PEDIDO ({{ $totalItems }})
        </a>
    </div>
@endif

@endsection

@push('scripts')
    {{-- Aquí irían scripts de JavaScript si fueran necesarios para el menú --}}
@endpush