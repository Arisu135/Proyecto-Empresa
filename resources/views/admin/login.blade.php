<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrador</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full">
        <div class="text-center mb-8">
            <div class="text-6xl mb-4">🔐</div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Acceso Administrador</h1>
            <p class="text-gray-600">Ingresa la contraseña para continuar</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-bold mb-2">Contraseña:</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-lg transition"
                    placeholder="••••••••"
                    required
                    autofocus
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-lg transition transform hover:scale-105"
            >
                Ingresar al Panel
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('catalogo.index') }}" class="text-gray-600 hover:text-gray-800 transition">
                ← Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
