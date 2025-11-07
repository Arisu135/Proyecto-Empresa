@extends('layouts.app')

@section('title', 'Historial de Ventas')

@section('content')

<h1>{{ $titulo }}</h1>

<div style="margin-bottom: 1rem;">
    <form method="GET" action="{{ route('admin.ventas') }}" style="display:flex; gap:8px; align-items:center;">
        <label>Filtrar:</label>
        <select name="filter" onchange="this.form.submit()">
            <option value="hoy" {{ $filter === 'hoy' ? 'selected' : '' }}>Hoy</option>
            <option value="todos" {{ $filter === 'todos' ? 'selected' : '' }}>Todos</option>
            <option value="fecha" {{ ($filter !== 'hoy' && $filter !== 'todos') ? 'selected' : '' }}>Por fecha</option>
        </select>

        <input type="date" name="filter" value="{{ $filter !== 'hoy' && $filter !== 'todos' ? $filter : '' }}">
        <button type="submit">Aplicar</button>
    </form>
</div>

<div style="margin-bottom:1rem; font-weight:bold;">Total Ventas: S/. {{ number_format($totalVentas ?? 0, 2) }}</div>

<table style="width:100%; border-collapse: collapse;">
    <thead>
        <tr style="background:#f0f0f0;">
            <th style="padding:8px; border:1px solid #ddd;">ID</th>
            <th style="padding:8px; border:1px solid #ddd;">Cliente</th>
            <th style="padding:8px; border:1px solid #ddd;">Total</th>
            <th style="padding:8px; border:1px solid #ddd;">Estado</th>
            <th style="padding:8px; border:1px solid #ddd;">Fecha</th>
            <th style="padding:8px; border:1px solid #ddd;">Detalles</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pedidos as $pedido)
            <tr>
                <td style="padding:8px; border:1px solid #ddd;">{{ $pedido->id }}</td>
                <td style="padding:8px; border:1px solid #ddd;">{{ $pedido->nombre_cliente ?? 'Cliente' }}</td>
                <td style="padding:8px; border:1px solid #ddd;">S/. {{ number_format($pedido->total, 2) }}</td>
                <td style="padding:8px; border:1px solid #ddd;">{{ $pedido->estado }}</td>
                <td style="padding:8px; border:1px solid #ddd;">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                <td style="padding:8px; border:1px solid #ddd;">
                    @if($pedido->detalles && $pedido->detalles->count())
                        <ul style="margin:0; padding-left:16px;">
                            @foreach($pedido->detalles as $d)
                                <li>{{ $d->cantidad }}x {{ $d->nombre_producto }} — S/. {{ number_format($d->subtotal, 2) }}</li>
                            @endforeach
                        </ul>
                    @else
                        —
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="padding:12px; text-align:center;">No se encontraron ventas para el filtro seleccionado.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:1rem;"><a href="/admin" style="text-decoration:none;">← Volver al Panel</a></div>

@endsection
