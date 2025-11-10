<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    /**
     * Obtiene los pedidos pendientes de impresión
     */
    public function getPendingPrints()
    {
        $pedidos = Pedido::with('detalles')
            ->where('pagado', true)
            ->where('impreso', false)
            ->where('eliminado', false)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $pedidos->count(),
            'pedidos' => $pedidos->map(function($pedido) {
                return [
                    'id' => $pedido->id,
                    'numero_mesa' => $pedido->numero_mesa,
                    'nombre_cliente' => $pedido->nombre_cliente,
                    'total' => $pedido->total,
                    'metodo_pago' => $pedido->metodo_pago,
                    'created_at' => $pedido->created_at->format('d/m/Y H:i'),
                    'detalles' => $pedido->detalles->map(function($detalle) {
                        return [
                            'cantidad' => $detalle->cantidad,
                            'nombre_producto' => $detalle->nombre_producto,
                            'subtotal' => $detalle->subtotal,
                        ];
                    }),
                ];
            }),
        ]);
    }

    /**
     * Marca un pedido como impreso
     */
    public function markAsPrinted(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);
        
        $pedido->impreso = true;
        $pedido->impreso_at = now();
        $pedido->save();

        return response()->json([
            'success' => true,
            'message' => "Pedido #{$id} marcado como impreso",
        ]);
    }
}
