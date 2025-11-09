@extends('layouts.app')

@section('title', 'Panel de Administración')

@push('styles')
<style>
  body { background: linear-gradient(135deg, #1a5928 0%, #2d7a3e 100%); min-height: 100vh; }
  .admin-card { transition: all 0.3s ease; }
  .admin-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.3); }
</style>
@endpush

@section('content')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <a href="{{ route('catalogo.index') }}" class="bg-white/20 hover:bg-white/30 text-white font-bold py-3 px-6 rounded-lg backdrop-blur-sm transition">
                ← Volver al Inicio
            </a>
            <a href="{{ route('admin.logout') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition">
                🚪 Cerrar Sesión
            </a>
        </div>
        
        <!-- Título -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-3 drop-shadow-lg">📊 Panel de Administración</h1>
            <p class="text-xl text-white/90">Rebel Jungle - Gestión Completa</p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Historial Ventas -->
            <a href="{{ route('admin.ventas') }}" class="admin-card bg-white p-8 rounded-2xl shadow-xl text-center">
                <div class="text-6xl mb-4">📊</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Historial Ventas</h3>
                <p class="text-gray-600">Ver todas las ventas</p>
            </a>

            <!-- Ventas Eliminadas -->
            <a href="{{ route('admin.ventas.eliminadas') }}" class="admin-card bg-white p-8 rounded-2xl shadow-xl text-center">
                <div class="text-6xl mb-4">🗑️</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Ventas Eliminadas</h3>
                <p class="text-gray-600">Historial completo</p>
            </a>

            <!-- Eliminadas Cocina -->
            <a href="{{ route('admin.ventas.eliminadas.cocina') }}" class="admin-card bg-white p-8 rounded-2xl shadow-xl text-center">
                <div class="text-6xl mb-4">🍳</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Eliminadas Cocina</h3>
                <p class="text-gray-600">Solo desde cocina</p>
            </a>

            <!-- Productos -->
            <a href="{{ route('productos.index') }}" class="admin-card bg-white p-8 rounded-2xl shadow-xl text-center">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Productos</h3>
                <p class="text-gray-600">Editar menú y precios</p>
            </a>

            <!-- Nuevo Producto -->
            <a href="{{ route('productos.create') }}" class="admin-card bg-white p-8 rounded-2xl shadow-xl text-center">
                <div class="text-6xl mb-4">➕</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Nuevo Producto</h3>
                <p class="text-gray-600">Añadir al menú</p>
            </a>
        </div>
    </div>
</div>
@endsection
