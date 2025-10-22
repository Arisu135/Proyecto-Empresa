@extends('layouts.app')

@section('title', '¡Pedido Enviado!')

@section('content')

    {{-- 
        Usamos la clase 'resumen-wrapper' para que tenga el fondo blanco centrado 
        y el resto de la pantalla se vea limpia (debido a la clase 'menu-full-screen' en el body).
    --}}
    <div class="resumen-wrapper" style="text-align: center; padding: 50px;">
        <h1 style="color: #4CAF50; font-size: 3em; margin-bottom: 20px;">🎉 ¡PEDIDO ENVIADO! 🎉</h1>

        {{-- 
            Recupera el mensaje que enviamos desde el controlador: 
            "¡Tu pedido ha sido enviado con éxito! Mesa #5" 
        --}}
        @if (session('success'))
            <p style="font-size: 1.5em; color: #333; margin-bottom: 40px;">{{ session('success') }}</p>
        @else
             {{-- Fallback en caso de que no haya mensaje en la sesión --}}
             <p style="font-size: 1.5em; color: #333; margin-bottom: 40px;">Gracias por tu compra.</p>
        @endif

        <p style="font-size: 1.1em; color: #777; margin-bottom: 50px;">
            Tu orden está siendo preparada. ¡Disfruta la selva!
        </p>

        {{-- Usamos la clase de botón definida para consistencia --}}
        <a href="{{ route('catalogo.index') }}" class="btn-finalizar" style="background-color: #2196F3;">
            Volver a la Pantalla de Inicio
        </a>
    </div>

@endsection