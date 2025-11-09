<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cocina - Pedidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#4d2925',
                        'brand-accent': '#ff9800',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FFFFFF; }
        .pedido-card { transition: transform 0.2s, box-shadow 0.2s; }
        .pedido-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(77, 41, 37, 0.15); }
        .alerta-tiempo { animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
    </style>
</head>
<body>
    <header class="bg-white sticky top-0 z-10 mb-2 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-brand-dark">🍳 COCINA</h1>
                <a href="{{ route('catalogo.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg">
                    ← Volver
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-4 px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(!isset($pedidos) || $pedidos->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <p class="text-2xl text-gray-500">🎉 ¡No hay pedidos pendientes!</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @foreach ($pedidos as $pedido)
                    @php
                        $minutos = $pedido->created_at->diffInMinutes(now());
                        $alerta = $minutos > 10;
                    @endphp
                    <div class="pedido-card bg-white p-4 rounded-2xl shadow-md flex flex-col">
                        @if($alerta)
                            <div class="alerta-tiempo bg-red-500 text-white text-xs font-bold px-2 py-1 rounded mb-2 text-center">
                                ⚠️ +10 MIN
                            </div>
                        @endif
                        
                        <div class="text-center mb-3">
                            <h3 class="text-xl font-bold text-brand-dark">#{{ $pedido->id }}</h3>
                            <p class="text-sm text-gray-600">{{ $pedido->nombre_cliente }}</p>
                            @if($pedido->numero_mesa)
                                <p class="text-blue-600 font-bold">Mesa {{ $pedido->numero_mesa }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">{{ $minutos }} min</p>
                        </div>

                        <ul class="text-xs space-y-1 mb-3 flex-grow">
                            @foreach($pedido->detalles as $detalle)
                                <li class="border-b pb-1">
                                    <span class="font-bold">{{ $detalle->cantidad }}x</span> {{ $detalle->nombre_producto }}
                                </li>
                            @endforeach
                        </ul>

                        <form action="{{ route('mesas.actualizarEstado', $pedido) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="estado" class="w-full p-2 text-sm border border-gray-300 rounded-lg" onchange="this.form.submit()">
                                <option value="Listo" @if($pedido->estado == 'Listo') selected @endif>✅ Listo</option>
                                <option value="Cancelado" @if($pedido->estado == 'Cancelado') selected @endif>❌ Cancelar</option>
                            </select>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
