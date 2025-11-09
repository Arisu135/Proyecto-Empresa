<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Rebel Jungle</title>
    
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    html, body {
        height: 100vh;
        overflow: hidden;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .kiosko-container {
        background: #ffffff;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
    }

    .admin-link {
        position: fixed;
        top: 1rem;
        right: 1rem;
        background: #333;
        color: #fff;
        padding: 0.75rem 1.25rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        z-index: 1000;
    }

    .logo-section img {
        width: 220px;
        height: 220px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .qr-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }

    .qr-section svg {
        width: 200px;
        height: 200px;
        border: 4px solid #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .qr-text {
        font-size: 0.875rem;
        color: #3e2723;
        font-weight: 600;
    }

    .buttons-section {
        display: flex;
        gap: 1.5rem;
        width: 100%;
        max-width: 600px;
        justify-content: center;
    }
    
    .btn-main {
        width: 250px;
        padding: 1.25rem;
        border: none;
        color: white;
        border-radius: 0.75rem;
        font-size: 1.25rem;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        transition: all 0.2s;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-main:active {
        transform: scale(0.98);
    }

    @media (max-width: 768px) {
        .kiosko-container {
            padding: 1rem;
        }

        .logo-section img {
            width: 140px;
            height: 140px;
        }
        
        .qr-section svg {
            width: 130px;
            height: 130px;
        }
        
        .buttons-section {
            flex-direction: column;
            max-width: 100%;
            gap: 1rem;
        }
        
        .btn-main {
            width: 100%;
            font-size: 1.125rem;
        }

        .qr-text {
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 768px) and (orientation: landscape) {
        .logo-section img {
            width: 120px;
            height: 120px;
        }
        
        .qr-section svg {
            width: 120px;
            height: 120px;
        }
        
        .btn-main {
            padding: 1rem;
            font-size: 1rem;
        }
    }
</style>
</head>
<body>
    <div class="kiosko-container">
        <a href="{{ route('admin.panel') }}" class="admin-link">Admin</a>
        
        <div class="logo-section">
            <img src="{{ asset('img/logo.png') }}" alt="Rebel Jungle Logo">
        </div>

        <div class="qr-section">
            {!! QrCode::size(230)->generate('https://www.instagram.com/rebel_jungle_cafe_plantas_?igsh=NG8xZzJ2bTBpam5t') !!}
            <p class="qr-text">@REBEL_JUNGLE_CAFE_PLANTAS_</p>
        </div>

        <div class="buttons-section">
            <a href="{{ route('productos.menu', ['tipo_pedido' => 'Para Aqui']) }}" 
               class="btn-main" 
               style="background-color: #388e3c;">
                PARA AQUÍ
            </a>

            <a href="{{ route('productos.menu', ['tipo_pedido' => 'Para Llevar']) }}" 
               class="btn-main" 
               style="background-color: #e65100;">
                PARA LLEVAR
            </a>
        </div>
    </div>
</body>
</html>
