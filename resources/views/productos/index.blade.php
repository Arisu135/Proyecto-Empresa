@extends('layouts.app')

@section('title', 'Menú de Productos')

@section('content')

    <h1>Listado de Productos</h1>
    <a href="/productos/create">Crear Nuevo Producto</a>
    <hr>
    
    <div class="products-grid" style="display:flex; flex-wrap:wrap; gap:18px;">
    @foreach ($productos as $producto)
        <div class="producto" style="flex: 1 1 300px; background:#ecf9f0; padding:18px; border-radius:8px; border:1px solid #d8e9dd;">
            <h2 style="margin-top:0;">{{ $producto->nombre }}</h2>
            @if(!empty($producto->imagen_nombre))
                <img src="{{ asset('img/productos/' . $producto->imagen_nombre) }}" alt="{{ $producto->nombre }}" style="max-width:100%; height:auto; border-radius:6px; margin-bottom:10px;">
            @endif
            <p style="margin:6px 0;">Precio: <strong>S/. {{ number_format($producto->precio, 2) }}</strong></p>
            <p style="margin:6px 0;">Categoría: <strong>{{ optional($producto->categoria)->nombre ?? 'Sin categoría' }}</strong></p>

            <div class="acciones" style="margin-top:12px;">
                <a href="/productos/{{ $producto->id }}/edit" style="display:inline-block; background:#f0ad4e; color:#000; padding:6px 10px; border-radius:6px; text-decoration:none; margin-right:8px;">Editar</a>

                <form action="/productos/{{ $producto->id }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')" style="background:#d9534f; color:#fff; border:none; padding:6px 10px; border-radius:6px;">Eliminar</button>
                </form>
            </div>
        </div>
    @endforeach
    </div>

@endsection