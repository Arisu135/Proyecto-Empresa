@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
    <h1 style="text-align: center; color: #333; margin-bottom: 40px;">Panel Central de Administración</h1>

    <div style="display: flex; justify-content: center; gap: 30px;">

        {{-- Botón para Ver Pedidos --}}
        <a href="{{ route('pedidos.gestion') }}" style="
            display: block;
            width: 300px;
            padding: 30px;
            text-align: center;
            background-color: #2196F3; /* Azul */
            color: white;
            text-decoration: none;
            font-size: 1.5em;
            font-weight: bold;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
        " onmouseover="this.style.backgroundColor='#1976D2';" onmouseout="this.style.backgroundColor='#2196F3';">
            Ver Pedidos Recibidos
        </a>

        {{-- Botón para Gestionar Productos (Menú) --}}
        <a href="{{ route('productos.index') }}" style="
            display: block;
            width: 300px;
            padding: 30px;
            text-align: center;
            background-color: #FF9800; /* Naranja */
            color: white;
            text-decoration: none;
            font-size: 1.5em;
            font-weight: bold;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
        " onmouseover="this.style.backgroundColor='#FB8C00';" onmouseout="this.style.backgroundColor='#FF9800';">
            Gestionar Menú
        </a>

    </div>

    <p style="text-align: center; margin-top: 50px; color: #777;">
        Usa este panel para llevar el control de las órdenes y actualizar el menú del café.
    </p>
@endsection