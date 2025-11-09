@extends('layouts.app')

@section('title', 'Panel de Administración')

@push('styles')
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { 
    background: linear-gradient(135deg, #166534 0%, #15803d 50%, #16a34a 100%); 
    min-height: 100vh;
    overflow-x: hidden;
  }
  .admin-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    padding: 1rem;
  }
  .admin-card { 
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  .admin-card:hover { 
    transform: translateY(-5px); 
    box-shadow: 0 10px 20px rgba(0,0,0,0.2); 
  }
  .admin-card .icon { font-size: 3rem; margin-bottom: 1rem; }
  .admin-card h3 { font-size: 1.25rem; font-weight: bold; color: #1f2937; margin-bottom: 0.5rem; }
  .admin-card p { font-size: 0.875rem; color: #6b7280; }
  
  @media (max-width: 768px) {
    .admin-card { padding: 1.5rem; }
    .admin-card .icon { font-size: 2.5rem; }
    .admin-card h3 { font-size: 1.1rem; }
  }
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="max-w-7xl mx-auto w-full">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 gap-2">
            <a href="{{ route('catalogo.index') }}" class="bg-white/20 hover:bg-white/30 text-white font-bold py-2 px-4 rounded-lg backdrop-blur-sm transition text-sm md:text-base">
                ← Inicio
            </a>
            <a href="{{ route('admin.logout') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition text-sm md:text-base">
                🚪 Salir
            </a>
        </div>
        
        <!-- Título -->
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">Panel de Administración</h1>
            <p class="text-base md:text-lg text-white/90">Rebel Jungle</p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <a href="{{ route('admin.ventas') }}" class="admin-card">
                <div class="icon">📊</div>
                <h3>Historial Ventas</h3>
                <p>Ver todas las ventas</p>
            </a>

            <a href="{{ route('admin.ventas.eliminadas') }}" class="admin-card">
                <div class="icon">🗑️</div>
                <h3>Ventas Eliminadas</h3>
                <p>Historial completo</p>
            </a>

            <a href="{{ route('admin.ventas.eliminadas.cocina') }}" class="admin-card">
                <div class="icon">🍳</div>
                <h3>Eliminadas Cocina</h3>
                <p>Solo desde cocina</p>
            </a>

            <a href="{{ route('productos.index') }}" class="admin-card">
                <div class="icon">📝</div>
                <h3>Productos</h3>
                <p>Editar menú y precios</p>
            </a>

            <a href="{{ route('productos.create') }}" class="admin-card">
                <div class="icon">➕</div>
                <h3>Nuevo Producto</h3>
                <p>Añadir al menú</p>
            </a>
        </div>
    </div>
</div>
@endsection
