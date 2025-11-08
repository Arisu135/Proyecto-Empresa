<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al Kiosco</title>

    {{-- Usar solo estilos internos y el CSS de Kiosco si es necesario --}}
    <link rel="stylesheet" href="{{ asset('css/kiosco.css') }}">
    
    <style>
    /* --- Contenedor general del kiosco --- */
    .kiosko-body {
        background-color: #ffffff;
        margin: 0;
        padding: 20px; /* Añade padding para móviles */
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: flex-start; /* Alinea arriba para evitar desbordamiento */
        align-items: center;
        text-align: center;
        position: relative;
    }

    /* --- Logo circular --- */
    .logo-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 30px;
        margin-top: 5vh; /* Usa viewport height para posicionamiento inicial */
    }

    .logo-wrapper img {
        width: 200px; /* Tamaño ligeramente reducido */
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    }

    /* --- QR --- */
    .qr-wrapper {
        margin-bottom: 5vh; /* Ajuste para el espacio */
    }

    .qr-wrapper svg {
        width: 180px; /* Reducir el tamaño del QR para móviles */
        height: 180px;
        border: 5px solid #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .qr-text {
        margin-top: 10px;
        font-size: 16px; /* Letra más pequeña */
        color: #3e2723;
        font-weight: bold;
    }

    /* --- Botones --- */
    .kiosko-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        /* IMPORTANTE: Cambiar de absolute a relative para que fluya en móviles */
        position: relative; 
        bottom: auto; 
        left: auto;
        padding-top: 20px;
    }

    .button-group {
        display: flex;
        flex-direction: column; /* Apilar botones verticalmente en móviles */
        gap: 20px;
        width: 90%; /* Ocupar casi todo el ancho */
        max-width: 400px; /* Límite para que no sean demasiado anchos en desktop */
    }
    
    .btn-kiosko {
        /* ... (tus estilos de color y sombra se mantienen) ... */
        border: none;
        color: white;
        padding: 15px 0; /* Padding adaptado */
        border-radius: 10px;
        font-size: 20px; /* Letra más grande para tocar */
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        transition: transform 0.15s ease;
        letter-spacing: 0.5px;
        text-align: center;
        /* Quitar min-width para que el ancho se adapte al 90% */
        display: block; 
        text-decoration: none;
    }

    .btn-kiosko:hover {
        transform: scale(1.03); /* Ligeramente más pequeño el efecto hover */
    }

    /* --- Media Query para DESKTOP (pantallas grandes) --- */
    /* Cuando la pantalla es ancha, volvemos al diseño de dos columnas */
    @media (min-width: 768px) {
        .kiosko-body {
             /* Restaurar la justificación para centrar verticalmente en desktop */
            justify-content: center; 
        }
        
        .logo-wrapper {
            margin-bottom: 35px;
            margin-top: 0;
        }

        .logo-wrapper img {
            width: 250px;
            height: 250px;
        }

        .qr-wrapper {
             margin-bottom: 100px;
        }
        
        .qr-wrapper svg {
            width: 230px;
            height: 230px;
        }
        
        .kiosko-wrapper {
            /* Vuelve a la posición absoluta en la parte inferior para desktop */
            position: absolute; 
            bottom: 40px; 
            left: 0;
        }

        .button-group {
            flex-direction: row; /* Colocar botones horizontalmente */
            gap: 30px;
            width: 80%;
            max-width: 600px;
        }
        
        .btn-kiosko {
             min-width: 250px; 
             padding: 18px 0;
        }
    }
</style>
</head>
<body>
    <div class="kiosko-body">

        <!-- Botones de Administración -->
        <div style="position:fixed; top:18px; right:18px; z-index:1100; display: flex; gap: 10px;">
            <a href="{{ route('admin.panel') }}" 
               style="background:#333; color:#fff; padding:10px 14px; border-radius:8px; text-decoration:none; font-weight:700; box-shadow:0 4px 10px rgba(0,0,0,0.15);">
                Admin
            </a>
            <a href="{{ route('caja.index') }}" 
               style="background:#333; color:#fff; padding:10px 14px; border-radius:8px; text-decoration:none; font-weight:700; box-shadow:0 4px 10px rgba(0,0,0,0.15);">
                Caja
            </a>
            <a href="{{ route('mesas.index') }}" 
               style="background:#333; color:#fff; padding:10px 14px; border-radius:8px; text-decoration:none; font-weight:700; box-shadow:0 4px 10px rgba(0,0,0,0.15);">
                Mesas
            </a>
        </div>
        
        {{-- Aquí puedes agregar el mensaje de bienvenida "¡Bienvenido!" si quieres --}}

        {{-- LOGO REDONDO ENCIMA DEL QR --}}
        <div class="logo-wrapper">
            <img src="{{ asset('img/logo.png') }}" alt="Rebel Jungle Logo">
        </div>

        {{-- CONTENEDOR DEL CÓDIGO QR --}}
        <div class="qr-wrapper">
            {{-- Mantengo el uso de la librería QrCode --}}
            {!! QrCode::size(230)->generate('https://www.instagram.com/rebel_jungle_cafe_plantas_?igsh=NG8xZzJ2bTBpam5t') !!}
            <p class="qr-text">@REBEL_JUNGLE_CAFE_PLANTAS_</p>
        </div>

        {{-- BOTONES DE OPCIÓN (AHORA SON ENLACES PARA EVITAR FALLOS DE FORMULARIOS) --}}
        <div class="kiosko-wrapper">
            <div class="button-group">
                
                {{-- BOTÓN 1: PARA AQUÍ --}}
                {{-- Envía 'tipo_pedido=Para Aqui' a la ruta del menú --}}
                <a href="{{ route('productos.menu', ['tipo_pedido' => 'Para Aqui']) }}" 
                   class="btn-kiosko" 
                   style="background-color: #388e3c;">
                    PARA AQUÍ
                </a>

                {{-- BOTÓN 2: PARA LLEVAR --}}
                {{-- Envía 'tipo_pedido=Para Llevar' a la ruta del menú --}}
                <a href="{{ route('productos.menu', ['tipo_pedido' => 'Para Llevar']) }}" 
                   class="btn-kiosko" 
                   style="background-color: #e65100;">
                    PARA LLEVAR
                </a>
                
            </div>
        </div>
    </div>
</body>
</html>