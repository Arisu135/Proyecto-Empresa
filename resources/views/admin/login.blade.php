<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrador</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); width: 100%; max-width: 400px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 48px; margin-bottom: 10px;">🔐</div>
            <h1 style="font-size: 24px; font-weight: bold; color: #1f2937; margin: 0 0 8px 0;">Acceso Administrador</h1>
            <p style="color: #6b7280; margin: 0;">Ingresa tu contraseña</p>
        </div>

        @if(session('error'))
            <div style="background: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Contraseña</label>
                <input 
                    type="password" 
                    name="password" 
                    style="width: 100%; padding: 12px; border: 2px solid #d1d5db; border-radius: 8px; font-size: 16px; box-sizing: border-box;"
                    placeholder="••••••••"
                    required
                    autofocus
                >
            </div>

            <button 
                type="submit" 
                style="width: 100%; background: linear-gradient(135deg, #3b82f6, #06b6d4); color: white; font-weight: bold; padding: 12px; border: none; border-radius: 8px; font-size: 16px; cursor: pointer;"
            >
                Ingresar
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('catalogo.index') }}" style="color: #6b7280; text-decoration: none; font-size: 14px;">
                ← Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
