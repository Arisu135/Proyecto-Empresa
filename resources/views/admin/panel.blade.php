@extends('layouts.app')

@section('title', 'Panel de Administración')

@push('styles')
<style>
  body { background: linear-gradient(135deg, #1a5928 0%, #2d7a3e 100%); height: 100vh; overflow: hidden; }
  .admin-card { transition: all 0.3s ease; }
  .admin-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.3); }
</style>
@endpush

@section('content')
<div class="h-screen flex flex-col py-6 px-4">
    <div class="max-w-6xl mx-auto w-full">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('catalogo.index') }}" class="bg-white/20 hover:bg-white/30 text-white font-bold py-2 px-5 rounded-lg backdrop-blur-sm transition">
                ← Volver al Inicio
            </a>
            <a href="{{ route('admin.logout') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-5 rounded-lg transition">
                🚪 Cerrar Sesión
            </a>
        </div>
        
        <!-- Título -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2 drop-shadow-lg">📊 Panel de Administración</h1>
            <p class="text-lg text-white/90">Rebel Jungle - Gestión Completa</p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <a href="{{ route('admin.ventas') }}" class="admin-card bg-white p-6 rounded-2xl shadow-xl text-center">
                <div class="text-5xl mb-3">📊</div>
                <h3 class="text-xl font-bold text-gray-800 mb-1">Historial Ventas</h3>
                <p class="text-gray-600 text-sm">Ver todas las ventas</p>
            </a>

            <a href="{{ route('admin.ventas.eliminadas') }}" class="admin-card bg-white p-6 rounded-2xl shadow-xl text-center">
                <div class="text-5xl mb-3">🗑️</div>
                <h3 class="text-xl font-bold text-gray-800 mb-1">Ventas Eliminadas</h3>
                <p class="text-gray-600 text-sm">Historial completo</p>
            </a>

            <a href="{{ route('admin.ventas.eliminadas.cocina') }}" class="admin-card bg-white p-6 rounded-2xl shadow-xl text-center">
                <div class="text-5xl mb-3">🍳</div>
                <h3 class="text-xl font-bold text-gray-800 mb-1">Eliminadas Cocina</h3>
                <p class="text-gray-600 text-sm">Solo desde cocina</p>
            </a>

            <a href="{{ route('productos.index') }}" class="admin-card bg-white p-6 rounded-2xl shadow-xl text-center">
                <div class="text-5xl mb-3">📝</div>
                <h3 class="text-xl font-bold text-gray-800 mb-1">Productos</h3>
                <p class="text-gray-600 text-sm">Editar menú y precios</p>
            </a>

            <a href="{{ route('productos.create') }}" class="admin-card bg-white p-6 rounded-2xl shadow-xl text-center">
                <div class="text-5xl mb-3">➕</div>
                <h3 class="text-xl font-bold text-gray-800 mb-1">Nuevo Producto</h3>
                <p class="text-gray-600 text-sm">Añadir al menú</p>
            </a>
        </div>
    </div>
</div>
@endsection
