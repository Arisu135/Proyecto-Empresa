<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas Eliminadas</title>
    <link rel="stylesheet" href="{{ asset('css/admin-ventas.css') }}">
</head>
<body>
    <div class="ventas-container">
        <div class="ventas-header">
            <h1 class="ventas-title">🗑️ {{ $titulo }}</h1>
        </div>

        <div class="filtros-card">
            <div>
                <div class="stat-label">Total Perdido</div>
                <div class="stat-value red">S/. {{ number_format($totalPerdido ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Mesa</th>
                            <th>Total</th>
                            <th>Motivo</th>
                            <th>Fecha Eliminación</th>
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
                                <td style="font-weight: bold; color: #dc2626;">S/. {{ number_format($pedido->total, 2) }}</td>
                                <td>
                                    <span class="badge badge-red">
                                        {{ $pedido->motivo_eliminacion ?? 'Sin motivo' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $pedido->eliminado_at ? $pedido->eliminado_at->format('d/m/Y H:i') : 'N/A' }}
                                </td>
                                <td>
                                    @if($pedido->detalles && $pedido->detalles->count())
                                        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.75rem;">
                                            @foreach($pedido->detalles as $d)
                                                <li style="margin-bottom: 0.25rem;">
                                                    <strong>{{ $d->cantidad }}x</strong> {{ $d->nombre_producto }}
                                                    <span style="color: #6b7280;">— S/. {{ number_format($d->subtotal, 2) }}</span>
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
                                    No hay ventas eliminadas.
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
