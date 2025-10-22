@extends('layouts.app')

@section('title', 'Añadir Nuevo Producto')

@section('content')

    <h1 style="text-align: center; color: #388E3C; margin-bottom: 30px;">Crear Nuevo Ítem para el Menú</h1>
    <hr style="border-top: 1px solid #c8e6c9;">

    @if ($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de Creación --}}
    <form action="{{ route('productos.store') }}" method="POST" style="max-width: 500px; margin: 0 auto;">
        @csrf

        {{-- Nombre --}}
        <div style="margin-bottom: 15px;">
            <label for="nombre" style="display: block; font-weight: bold; margin-bottom: 5px;">Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        {{-- Descripción --}}
        <div style="margin-bottom: 15px;">
            <label for="descripcion" style="display: block; font-weight: bold; margin-bottom: 5px;">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required 
                      style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; resize: vertical;">{{ old('descripcion') }}</textarea>
        </div>

        {{-- Precio --}}
        <div style="margin-bottom: 15px;">
            <label for="precio" style="display: block; font-weight: bold; margin-bottom: 5px;">Precio (S/.):</label>
            <input type="number" step="0.01" id="precio" name="precio" value="{{ old('precio') }}" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        {{-- Categoría --}}
        <div style="margin-bottom: 25px;">
            <label for="categoria" style="display: block; font-weight: bold; margin-bottom: 5px;">Categoría:</label>
            <select id="categoria" name="categoria" required 
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="Combos">Combos</option>
                <option value="Piezas">Piezas</option>
                <option value="Bebidas">Bebidas</option>
                <option value="Postres">Postres</option>
            </select>
        </div>

        {{-- Botón de Guardar --}}
        <button type="submit" style="
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #4CAF50; /* Verde */
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s;
        " onmouseover="this.style.backgroundColor='#388E3C';" onmouseout="this.style.backgroundColor='#4CAF50';">
            Guardar Producto
        </button>
    </form>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('productos.index') }}" style="color: #007bff; text-decoration: none;">Volver a la Lista de Productos</a>
    </div>

@endsection