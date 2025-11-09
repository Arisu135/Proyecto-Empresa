<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al Kiosco</title>
    <link rel="stylesheet" href="{{ asset('css/kiosco.css') }}">
    
    <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    html, body {
        height: 100vh;
        overflow: hidden;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .kiosko-body {
        background-color: #ffffff;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
    }

    .logo-wrapper {
        flex-shrink: 0;
        margin-top: 1rem;
    }

    .logo-wrapper img {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .qr-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .qr-wrapper svg {
        width: 180px;
        height: 180px;
        border: 4px solid #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .qr-text {
        margin-top: 0.75rem;
        font-size: 0.875rem;
        color: #3e2723;
        font-weight: 600;
    }

    .kiosko-wrapper {
        width: 100%;
        flex-shrink: 0;
        margin-bottom: 1rem;
    }

    .button-group {
        display: flex;
        flex-direction: row;
        gap: 1rem;
        justify-content: center;
        align-items: center;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .btn-kiosko {
        flex: 1;
        border: none;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        font-size: 1.125rem;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        transition: all 0.2s ease;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 60px;
    }

    .btn-kiosko:active {
        transform: scale(0.98);
    }

    .admin-btn {
        position: fixed;
        top: 1rem;
        right: 1rem;
        background: #333;
        color: #fff;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        z-index: 1100;
        font-size: 0.875rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .logo-wrapper img {
            width: 160px;
            height: 160px;
        }
        
        .qr-wrapper svg {
            width: 150px;
            height: 150px;
        }
        
        .button-group {
            flex-direction: column;
            width: 100%;
            max-width: 100%;
            padding: 0 1rem;
        }
        
        .btn-kiosko {
            width: 100%;
            max-width: 100%;
            font-size: 1rem;
            padding: 1rem;
        }

        .qr-text {
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 768px) and (orientation: landscape) {
        .logo-wrapper {
            margin-top: 0.5rem;
        }
        
        .logo-wrapper img {
            width: 120px;
            height: 120px;
        }
        
        .qr-wrapper svg {
            width: 120px;
            height: 120px;
        }
        
        .btn-kiosko {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            min-height: 50px;
        }
    }
</style>
</head>
<body>
    <div class="kiosko-body">
        <a href="{{ route('admin.panel') }}" class="admin-btn">Admin</a>
        
        <div class="logo-wrapper">
            <img src="{{ asset('img/logo.png') }}" alt="Rebel Jungle Logo">
        </div>

        <div class="qr-wrapper">
            {!! QrCode::size(230)->generate('https://www.instagram.com/rebel_jungle_cafe_plantas_?igsh=NG8xZzJ2bTBpam5t') !!}
            <p class="qr-text">@REBEL_JUNGLE_CAFE_PLANTAS_</p>
        </div>

        <div class="kiosko-wrapper">
            <div class="button-group">
                <a href="{{ route('productos.menu', ['tipo_pedido' => 'Para Aqui']) }}" 
                   class="btn-kiosko" 
                   style="background-color: #388e3c;">
                    PARA AQUÍ
                </a>

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
