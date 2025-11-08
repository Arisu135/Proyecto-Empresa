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
    <div class="text-center py-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Bienvenido al Panel de Administración</h1>
        <p class="text-xl text-gray-600">Usa el menú superior para navegar entre las secciones</p>
    </div>
</div>
@endsection