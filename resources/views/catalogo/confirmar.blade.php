@extends('layouts.app')

@section('title', 'Resumen del Pedido')

@push('styles')
    {{-- ¡Solo el enlace al CSS externo! --}}
    <link rel="stylesheet" href="{{ asset('css/kiosco.css') }}">
@endpush

@section('content')

<div class="resumen-container">

    <div class="header-buttons">
        <a href="{{ route('productos.menu') }}" class="btn-inicio">
            &lt; Añadir más productos
        </a>
        <h1 class="menu-title">Resumen de tu Pedido</h1>
        <a href="{{ route('carrito.limpiar') }}" class="btn-empezar-nuevo">
            Empezar de nuevo
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FORMULARIO PRINCIPAL QUE AHORA ENVÍA EL NOMBRE Y EL PEDIDO --}}
    @if (!empty($carrito))
    <form action="{{ route('pedido.finalizar') }}" method="POST">
        @csrf

        {{-- INICIO: CAMPO DE ENTRADA PARA EL NOMBRE DEL CLIENTE --}}
        @php
            // Obtener el nombre de la sesión (si existe) o inicializarlo vacío.
            // Esto es solo para recordar el valor si el usuario regresa.
            $nombreCliente = Session::get('nombre_cliente', ''); 
        @endphp

        <div class="name-input-group p-4 bg-white shadow-lg rounded-lg mb-6 border-b-4 border-gray-200">
            <label for="nombre_cliente" class="block text-xl font-extrabold text-gray-700 mb-2">
                Identificación del Pedido:
            </label>
            <input 
                type="text" 
                name="nombre_cliente" 
                id="nombre_cliente" 
                class="w-full p-3 border-2 border-gray-400 rounded-lg focus:border-green-600 focus:ring-2 focus:ring-green-300 text-2xl font-semibold" 
                placeholder="Ingresa tu nombre para el pedido"
                value="{{ $nombreCliente }}"
                required {{-- Hacemos que este campo sea obligatorio --}}
            >
            <p class="text-sm text-gray-500 mt-2">Usaremos este nombre para llamarte cuando tu pedido esté listo.</p>
        </div>
        {{-- FIN: CAMPO DE ENTRADA PARA EL NOMBRE DEL CLIENTE --}}

        <div class="resumen-card">
            
            {{-- EL BLOQUE ANTERIOR DE $MESA HA SIDO ELIMINADO AQUÍ --}}

            @forelse ($carrito as $itemKey => $item)
                <div class="item-row">
                    <div class="item-info">
                        <p class="item-name">{{ $item['nombre'] }}</p>
                        <small>Precio Unitario: S/. {{ number_format($item['precio'], 2) }}</small>
                    </div>

                    <div class="item-quantity">
                        {{ $item['cantidad'] }}x
                    </div>
                    
                    <div class="item-price">
                        S/. {{ number_format($item['subtotal'], 2) }}
                    </div>
                    
                    {{-- Botones de control de cantidad --}}
                    <div class="item-actions">
                        <a href="{{ route('carrito.restar', ['itemKey' => $itemKey]) }}" class="btn-update-cart">
                            <i class="fas fa-minus-circle"></i> 
                        </a>
                        <a href="{{ route('carrito.eliminar', ['itemKey' => $itemKey]) }}" class="btn-remove-item">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-center" style="font-size: 1.2em; color: #888;">Tu carrito está vacío. ¡Añade algo delicioso!</p>
            @endforelse

            @if (!empty($carrito))
                <div class="total-section">
                    <span>TOTAL:</span>
                    <span style="color: #e74c3c;">S/. {{ number_format($total, 2) }}</span>
                </div>
            @endif

        </div>

        {{-- BOTÓN DE CONFIRMACIÓN (si el carrito no está vacío) --}}
        <button type="submit" class="btn-finalizar-resumen">
            CONFIRMAR Y ENVIAR A COCINA
        </button>
        
        <p class="text-center mt-3 text-muted">Una vez confirmado, el pedido se enviará inmediatamente.</p>
    
    </form>
    @endif
</div>

@endsection