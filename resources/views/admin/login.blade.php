<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrador</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">🔐 Acceso Administrador</h1>
            <p class="text-gray-600">Ingresa la contraseña para continuar</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
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
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg"
                    placeholder="Ingresa tu contraseña"
                    required
                    autofocus
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition"
            >
                Ingresar
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('catalogo.index') }}" class="text-gray-600 hover:text-gray-800">
                ← Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
