<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Administrador</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="login-card rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-cyan-400 p-8 text-center">
            <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Inicio de Sesión</h1>
            <p class="text-blue-100">Panel de Administración</p>
        </div>

        <!-- Form -->
        <div class="p-8">
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6 flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-bold">Error de autenticación</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Contraseña de Administrador
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-lg transition"
                            placeholder="Ingresa tu contraseña"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-bold py-3 px-4 rounded-xl transition transform hover:scale-[1.02] shadow-lg hover:shadow-xl"
                >
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Acceder al Panel
                    </span>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                <a href="{{ route('catalogo.index') }}" class="text-gray-600 hover:text-blue-500 transition font-medium inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</body>
</html>
