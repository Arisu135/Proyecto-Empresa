<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Ventas</title>
    <link rel="stylesheet" href="{{ asset('css/admin-ventas.css') }}">
</head>
<body>
    <div class="ventas-container">
        <div class="ventas-header">
            <h1 class="ventas-title">{{ $titulo }}</h1>
        </div>

        <form method="GET" action="{{ route('admin.ventas') }}" class="filtros-card">
            <div class="filtros-grid">
                <div class="filtro-group">
                    <label>Filtrar por Fecha:</label>
                    <select name="filter" onchange="this.form.submit()">
                        <option value="hoy" {{ $filter === 'hoy' ? 'selected' : '' }}>Hoy</option>
                        <option value="todos" {{ $filter === 'todos' ? 'selected' : '' }}>Todos</option>
                    </select>
                </div>
                
                <div class="filtro-group">
                    <label>Fecha Específica:</label>
                    <input type="date" name="filter" value="{{ $filter !== 'hoy' && $filter !== 'todos' ? $filter : '' }}" onchange="this.form.submit()">
                </div>

                <div class="filtro-group">
                    <label>Filtrar por Categoría:</label>
                    <select name="categoria" onchange="this.form.submit()">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ $categoriaId == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filtro-group">
                    <label>Buscar Producto:</label>
                    <input type="text" name="producto" value="{{ request('producto') }}" placeholder="Nombre del producto..." class="w-full p-2 border border-gray-300 rounded">
                </div>

                <div class="filtro-group" style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn btn-green" style="width: 100%;">Buscar</button>
                </div>
            </div>
            
            <a href="{{ route('admin.ventas') }}" class="btn-link">← Limpiar filtros</a>
        </form>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Ventas</div>
                <div class="stat-value green">S/. {{ number_format($totalVentas ?? 0, 2) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Pedidos</div>
                <div class="stat-value blue">{{ $totalPedidos ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Promedio por Pedido</div>
                <div class="stat-value purple">S/. {{ $totalPedidos > 0 ? number_format($totalVentas / $totalPedidos, 2) : '0.00' }}</div>
            </div>
        </div>

        @if(!empty($ventasPorCategoria))
        <div class="filtros-card">
            <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem;">📊 Ventas por Categoría</h2>
            <div class="categoria-grid">
                @foreach($ventasPorCategoria as $catNombre => $datos)
                    <div class="categoria-card">
                        <div class="categoria-nombre">{{ $catNombre }}</div>
                        <div class="categoria-cantidad">Cantidad: {{ $datos['cantidad'] }} unidades</div>
                        <div class="categoria-total">S/. {{ number_format($datos['total'], 2) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="table-card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Mesa</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos as $pedido)
                            <tr>
                                <td>#{{ $pedido->id }}</td>
                                <td>{{ $pedido->nombre_cliente ?? 'Cliente' }}</td>
                                <td>
                                    @if($pedido->numero_mesa)
                                        <span class="badge badge-blue">Mesa {{ $pedido->numero_mesa }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td style="font-weight: bold; color: #16a34a;">S/. {{ number_format($pedido->total, 2) }}</td>
                                <td>
                                    <span class="badge 
                                        @if($pedido->estado == 'Entregado') badge-green
                                        @elseif($pedido->estado == 'Cancelado') badge-red
                                        @else badge-yellow
                                        @endif
                                    ">{{ $pedido->estado }}</span>
                                </td>
                                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($pedido->detalles && $pedido->detalles->count())
                                        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.75rem;">
                                            @foreach($pedido->detalles as $d)
                                                <li style="margin-bottom: 0.25rem;">
                                                    <strong>{{ $d->cantidad }}x</strong> {{ $d->nombre_producto }}
                                                    @if($d->producto && $d->producto->categoria)
                                                        <span style="color: #9ca3af;">({{ $d->producto->categoria->nombre }})</span>
                                                    @endif
                                                    <span style="color: #16a34a;">— S/. {{ number_format($d->subtotal, 2) }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: #6b7280;">
                                    No se encontraron ventas para el filtro seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <a href="{{ route('admin.panel') }}" class="btn-link">← Volver al Panel</a>
        </div>
    </div>


</body>
</html>
