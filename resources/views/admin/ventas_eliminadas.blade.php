@extends('layouts.app')

@section('title', 'Ventas Eliminadas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-red-600 mb-6">🗑️ {{ $titulo }}</h1>

    <div class="bg-red-100 p-4 rounded-lg shadow mb-6">
        <p class="text-sm text-gray-600">Total Perdido</p>
        <p class="text-2xl font-bold text-red-700">S/. {{ number_format($totalPerdido ?? 0, 2) }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Cliente</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Mesa</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Total</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Motivo</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Fecha Eliminación</th>
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
                        <td class="px-4 py-3 text-sm font-bold text-red-600">S/. {{ number_format($pedido->total, 2) }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">
                                {{ $pedido->motivo_eliminacion ?? 'Sin motivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $pedido->eliminado_at ? $pedido->eliminado_at->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($pedido->detalles && $pedido->detalles->count())
                                <ul class="text-xs space-y-1">
                                    @foreach($pedido->detalles as $d)
                                        <li>
                                            <span class="font-bold">{{ $d->cantidad }}x</span> {{ $d->nombre_producto }}
                                            <span class="text-gray-600">— S/. {{ number_format($d->subtotal, 2) }}</span>
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
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            No hay ventas eliminadas.
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
@endsection
