@extends('layouts.app')

@section('title', 'Panel de Administración')

@push('styles')
<style>
  /* Ensure admin header/nav sits at the top and is centered */
  body.admin-body { display: block !important; }
  header .admin-nav, .admin-nav { justify-content: center !important; max-width: 1100px; margin: 10px auto !important; }
  header .admin-nav a { padding: 8px 14px; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('catalogo.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            ← Volver al Inicio
        </a>
        <a href="{{ route('admin.logout') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            🚪 Cerrar Sesión
        </a>
    </div>
    
    <div class="text-center py-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">📊 Panel de Administración</h1>
        <p class="text-xl text-gray-600 mb-8">Gestiona tu negocio desde aquí</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
        <a href="{{ route('admin.gestion') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-6 rounded-lg shadow-lg text-center transition">
            <div class="text-4xl mb-2">🍳</div>
            <h3 class="text-xl font-bold">Cocina</h3>
            <p class="text-sm mt-2">Gestionar pedidos</p>
        </a>

        <a href="{{ route('admin.ventas') }}" class="bg-green-500 hover:bg-green-600 text-white p-6 rounded-lg shadow-lg text-center transition">
            <div class="text-4xl mb-2">📊</div>
            <h3 class="text-xl font-bold">Historial Ventas</h3>
            <p class="text-sm mt-2">Ver ventas realizadas</p>
        </a>

        <a href="{{ route('productos.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white p-6 rounded-lg shadow-lg text-center transition">
            <div class="text-4xl mb-2">📝</div>
            <h3 class="text-xl font-bold">Productos</h3>
            <p class="text-sm mt-2">Editar menú y precios</p>
        </a>

        <a href="{{ route('productos.create') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-6 rounded-lg shadow-lg text-center transition">
            <div class="text-4xl mb-2">➕</div>
            <h3 class="text-xl font-bold">Nuevo Producto</h3>
            <p class="text-sm mt-2">Añadir al menú</p>
        </a>
    </div>
</div>
@endsection