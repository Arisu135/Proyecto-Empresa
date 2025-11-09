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
    html, body {
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow: hidden;
    }
    
    .kiosko-body {
        background-color: #ffffff;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        text-align: center;
        padding: 20px;
        box-sizing: border-box;
    }

    /* --- Logo circular --- */
    .logo-wrapper {
        flex-shrink: 0;
        margin-top: 10px;
    }

    .logo-wrapper img {
        width: 220px;
        height: 220px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    }

    /* --- QR --- */
    .qr-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        padding-top: 20px;
    }

    .qr-wrapper svg {
        width: 180px;
        height: 180px;
        border: 5px solid #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .qr-text {
        margin-top: 10px;
        font-size: 14px;
        color: #3e2723;
        font-weight: bold;
    }

    /* --- Botones --- */
    .kiosko-wrapper {
        width: 100%;
        flex-shrink: 0;
        margin-bottom: 20px;
    }

    .button-group {
        display: flex;
        flex-direction: row;
        gap: 15px;
        justify-content: center;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .btn-kiosko {
        flex: 1;
        border: none;
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        transition: transform 0.15s ease;
        text-align: center;
        text-decoration: none;
        min-width: 200px;
        max-width: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-kiosko:active {
        transform: scale(0.98);
    }

    /* --- Media Query para móviles verticales --- */
    @media (max-width: 768px) and (orientation: portrait) {
        .logo-wrapper img {
            width: 170px;
            height: 170px;
        }
        
        .qr-wrapper svg {
            width: 160px;
            height: 160px;
        }
        
        .button-group {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-kiosko {
            min-width: 100%;
            max-width: 100%;
            font-size: 16px;
        }
    }
    
    /* --- Media Query para móviles horizontales --- */
    @media (max-width: 768px) and (orientation: landscape) {
        .logo-wrapper {
            margin-top: 5px;
        }
        
        .logo-wrapper img {
            width: 130px;
            height: 130px;
        }
        
        .qr-wrapper {
            padding-top: 10px;
        }
        
        .qr-wrapper svg {
            width: 130px;
            height: 130px;
        }
        
        .qr-text {
            font-size: 12px;
        }
        
        .btn-kiosko {
            padding: 12px 20px;
            font-size: 16px;
            min-width: 180px;
        }
    }
</style>
</head>
<body>
    <div class="kiosko-body">

        <!-- Botón de Administración -->
        <div style="position:fixed; top:10px; right:10px; z-index:1100;">
            <a href="{{ route('admin.panel') }}" 
               style="background:#333; color:#fff; padding:10px 14px; border-radius:8px; text-decoration:none; font-weight:700; box-shadow:0 4px 10px rgba(0,0,0,0.15);">
                Admin
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