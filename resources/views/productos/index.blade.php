@extends('layouts.app')

@section('title', 'Menú de Productos')

@section('content')

    <h1>Listado de Productos</h1>
    <a href="/productos/create">Crear Nuevo Producto</a>
    <hr>
    
    @foreach ($productos as $producto)
        <div class="producto">
            <h2>{{ $producto->nombre }}</h2>
            <p>Precio: S/. {{ number_format($producto->precio, 2) }}</p>
            <p>Categoría: {{ $producto->categoria }}</p>
            
            <div class="acciones">
                <a href="/productos/{{ $producto->id }}/edit">Editar</a> 

                <form action="/productos/{{ $producto->id }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">Eliminar</button>
                </form>
            </div>
        </div>
    @endforeach

@endsection