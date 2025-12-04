<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function getPendingPrints()
    {
        $pedidos = Pedido::with('detalles')
            ->where('impreso', false)
            ->where('eliminado', false)
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($pedidos as $pedido) {
            $pedido->impreso = true;
            $pedido->save();
        }

        return response()->json([
            'success' => true,
            'pedidos' => $pedidos->map(function($pedido) {
                return [
                    'id' => $pedido->id,
                    'numero_mesa' => $pedido->numero_mesa,
                    'nombre_cliente' => $pedido->nombre_cliente,
                    'total' => $pedido->total,
                    'tipo_pedido' => $pedido->tipo_pedido,
                    'metodo_pago' => $pedido->metodo_pago ?? 'pendiente',
                    'created_at' => $pedido->created_at->format('d/m/Y H:i'),
                    'detalles' => $pedido->detalles->map(function($detalle) {
                        return [
                            'cantidad' => $detalle->cantidad,
                            'nombre_producto' => $detalle->nombre_producto,
                            'subtotal' => $detalle->subtotal,
                            'opciones_personalizadas' => $detalle->opciones_personalizadas,
                        ];
                    }),
                ];
            }),
        ]);
    }
}
