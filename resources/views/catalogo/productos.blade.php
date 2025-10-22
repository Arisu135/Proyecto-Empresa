@extends('layouts.app')

@section('title', 'Nuestro Menú - Rebel Jungle Café')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kiosco.css') }}">
@endpush

@section('content')
@php
    $carrito = Session::get('carrito', []);
    $total = array_sum(array_column($carrito, 'subtotal')); 
    $count = array_sum(array_column($carrito, 'cantidad')); 
    $totalFormatted = number_format($total, 2);
@endphp

<div class="main-container">

    <div class="header-buttons">
        <a href="{{ route('catalogo.index') }}" class="btn-inicio">
            &lt; Volver al Inicio
        </a>
        <h1 class="menu-title">Nuestro Menú - Rebel Jungle Café</h1>
        <a href="{{ route('carrito.limpiar') }}" class="btn-empezar-nuevo">
            Empezar de nuevo
        </a>
    </div>

    <div class="menu-grid-container">
        @foreach($productos as $producto)
        
        <a href="#" class="product-card-kiosco link-no-style" 
            data-id="{{ $producto->id }}"
            data-nombre="{{ $producto->nombre }}"
            data-descripcion="{{ $producto->descripcion }}"
            data-precio="{{ number_format($producto->precio, 2, '.', '') }}"
            data-imagen="{{ asset('storage/' . $producto->imagen) }}"
            onclick="openProductModal(this)">
            
            <div class="card-image">
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" onerror="this.onerror=null;this.src='{{ asset('images/default-food.png') }}';" class="product-image-kiosco">
            </div>
            
            <div class="card-details-minimal">
                <h3 class="product-title-kiosco">{{ $producto->nombre }}</h3>
            </div>
        </a>
        @endforeach
    </div>

    @if ($count > 0)
        <div class="floating-summary-bar">
            <div class="summary-info">
                @php $unidades = array_sum(array_column($carrito, 'cantidad')); @endphp
                <span>{{ $unidades }} {{ $unidades === 1 ? 'Producto' : 'Productos' }} | Total: S/. {{ $totalFormatted }}</span>
            </div>
            <div>
                <a href="{{ route('pedido.resumen') }}" class="btn-finalize-order">
                    Ver Resumen
                </a>
            </div>
        </div>
    @endif
    
</div>

{{-- ========================================================= --}}
{{-- MODAL DE SELECCIÓN (VENTANA SECUNDARIA) --}}
{{-- CAMBIO CLAVE: Cambiado de class="modal" a class="modal-overlay" --}}
{{-- ========================================================= --}}
<div id="selectionModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" onclick="closeProductModal()">&times;</span>
        
        <h2 id="modalTitle" class="modal-title"></h2>
        
        <div class="modal-details-row">
            <img id="modalImage" src="" alt="Producto" class="modal-image">
            <div style="overflow: hidden;">
                <p id="modalDescription" class="modal-description-text"></p>
                <p class="modal-price">Desde: S/. <span id="modalPrice"></span></p>
            </div>
        </div>
        
        <div id="modalOptionsContent">
        </div>

        <button class="btn-seleccionar" onclick="selectProductAndProceed()">
            Seleccionar
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentProductId = null;
    let currentProductPrice = 0;
    let currentProductName = '';

    function openProductModal(element) {
        currentProductId = element.dataset.id;
        currentProductPrice = parseFloat(element.dataset.precio);
        currentProductName = element.dataset.nombre;
        const descripcion = element.dataset.descripcion;
        const imagen = element.dataset.imagen;
        const precio = element.dataset.precio;

        document.getElementById('modalTitle').textContent = currentProductName;
        document.getElementById('modalDescription').textContent = descripcion;
        document.getElementById('modalPrice').textContent = precio;
        document.getElementById('modalImage').src = imagen;
        
        const btn = document.querySelector('#selectionModal .btn-seleccionar');
        if (currentProductName.toLowerCase().includes('combo')) { 
            btn.textContent = 'Personalizar Pedido';
        } else {
            btn.textContent = 'Seleccionar y Añadir';
        }

        document.getElementById('selectionModal').style.display = 'flex';
    }

    function closeProductModal() {
        document.getElementById('selectionModal').style.display = 'none';
        currentProductId = null;
        currentProductPrice = 0;
        currentProductName = '';
    }

    function selectProductAndProceed() {
        if (currentProductId) {
            if (currentProductName.toLowerCase().includes('combo') || currentProductName.toLowerCase().includes('individual')) {
                // REDIRECCIÓN: Si es complejo, vamos a la página de detalle
                window.location.href = "{{ url('pedido/detalle') }}/" + currentProductId;
                
            } else {
                // Si es un item simple (ej: Gaseosa), lo añade directamente
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ url('carrito/agregar') }}/" + currentProductId; 
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="cantidad" value="1">
                    <input type="hidden" name="precio_final_unitario" value="${currentProductPrice.toFixed(2)}">
                `; 
                
                document.body.appendChild(form);
                form.submit();
            }
        }
        closeProductModal();
    }

    window.onclick = function(event) {
        let modal = document.getElementById('selectionModal');
        if (event.target == modal) {
            closeProductModal();
        }
    }
</script>
@endpush