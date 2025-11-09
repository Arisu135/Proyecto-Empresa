@extends('layouts.app')

@section('title', 'Historial de Ventas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ $titulo }}</h1>

    <!-- Filtros -->
    <form method="GET" action="{{ route('admin.ventas') }}" class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Filtrar por Fecha:</label>
                <select name="filter" class="w-full p-2 border border-gray-300 rounded" onchange="this.form.submit()">
                    <option value="hoy" {{ $filter === 'hoy' ? 'selected' : '' }}>Hoy</option>
                    <option value="todos" {{ $filter === 'todos' ? 'selected' : '' }}>Todos</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Fecha Específica:</label>
                <input type="date" name="filter" value="{{ $filter !== 'hoy' && $filter !== 'todos' ? $filter : '' }}" class="w-full p-2 border border-gray-300 rounded" onchange="this.form.submit()">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Filtrar por Categoría:</label>
                <select name="categoria" class="w-full p-2 border border-gray-300 rounded" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ $categoriaId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('admin.ventas') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                ← Limpiar filtros
            </a>
        </div>
    </form>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-100 p-4 rounded-lg shadow">
            <p class="text-sm text-gray-600">Total Ventas</p>
            <p class="text-2xl font-bold text-green-700">S/. {{ number_format($totalVentas ?? 0, 2) }}</p>
        </div>
        <div class="bg-blue-100 p-4 rounded-lg shadow">
            <p class="text-sm text-gray-600">Total Pedidos</p>
            <p class="text-2xl font-bold text-blue-700">{{ $totalPedidos ?? 0 }}</p>
        </div>
        <div class="bg-purple-100 p-4 rounded-lg shadow">
            <p class="text-sm text-gray-600">Promedio por Pedido</p>
            <p class="text-2xl font-bold text-purple-700">S/. {{ $totalPedidos > 0 ? number_format($totalVentas / $totalPedidos, 2) : '0.00' }}</p>
        </div>
    </div>

    <!-- Ventas por Categoría -->
    @if(!empty($ventasPorCategoria))
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">📊 Ventas por Categoría</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($ventasPorCategoria as $catNombre => $datos)
                <div class="border border-gray-200 p-4 rounded-lg">
                    <p class="font-bold text-gray-700">{{ $catNombre }}</p>
                    <p class="text-sm text-gray-600">Cantidad: {{ $datos['cantidad'] }} unidades</p>
                    <p class="text-lg font-bold text-green-600">S/. {{ number_format($datos['total'], 2) }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tabla de Pedidos -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Cliente</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Mesa</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Total</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Estado</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Fecha</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Acción</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Detalles</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($pedidos as $pedido)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">#{{ $pedido->id }}</td>
                        <td class="px-4 py-3 text-sm">{{ $pedido->nombre_cliente ?? 'Cliente' }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($pedido->numero_mesa)
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">Mesa {{ $pedido->numero_mesa }}</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-bold text-green-600">S/. {{ number_format($pedido->total, 2) }}</pd>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($pedido->estado == 'Entregado') bg-green-100 text-green-800
                                @elseif($pedido->estado == 'Cancelado') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif
                            ">{{ $pedido->estado }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-sm">
                            <button onclick="eliminarVenta({{ $pedido->id }})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-bold">
                                🗑️ Eliminar
                            </button>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($pedido->detalles && $pedido->detalles->count())
                                <ul class="text-xs space-y-1">
                                    @foreach($pedido->detalles as $d)
                                        <li>
                                            <span class="font-bold">{{ $d->cantidad }}x</span> {{ $d->nombre_producto }}
                                            @if($d->producto && $d->producto->categoria)
                                                <span class="text-gray-400">({{ $d->producto->categoria->nombre }})</span>
                                            @endif
                                            <span class="text-green-600">— S/. {{ number_format($d->subtotal, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            No se encontraron ventas para el filtro seleccionado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.panel') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
            ← Volver al Panel
        </a>
    </div>
</div>

<div id="modalEliminar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:12px; max-width:400px; width:90%;">
        <h2 style="font-size:20px; font-weight:bold; margin-bottom:15px; color:#ef4444; text-align:center;">⚠️ Eliminar Venta</h2>
        <p style="margin-bottom:20px; color:#4b5563; text-align:center;">Indica el motivo de eliminación:</p>
        
        <form id="formEliminarVenta" method="POST" action="">
            @csrf
            @method('DELETE')
            <textarea name="motivo" rows="3" placeholder="Motivo de eliminación..." required style="width:100%; padding:10px; border:2px solid #d1d5db; border-radius:8px; margin-bottom:15px;"></textarea>
            
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="cerrarModalEliminar()" style="flex:1; padding:12px; background:#6b7280; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">
                    Cancelar
                </button>
                <button type="submit" style="flex:1; padding:12px; background:#ef4444; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">
                    Eliminar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function eliminarVenta(id) {
    document.getElementById('formEliminarVenta').action = '/admin/ventas/' + id + '/eliminar';
    document.getElementById('modalEliminar').style.display = 'flex';
}

function cerrarModalEliminar() {
    document.getElementById('modalEliminar').style.display = 'none';
}
</script>
@endsection
