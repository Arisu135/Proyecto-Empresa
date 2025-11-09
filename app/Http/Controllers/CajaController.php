<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('detalles')
            ->where('estado', 'Entregado')
            ->where('pagado', false)
            ->where('eliminado', false)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('caja.index', compact('pedidos'));
    }

    public function marcarPagado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,yape',
        ]);
        
        $pedido->pagado = true;
        $pedido->metodo_pago = $request->metodo_pago;
        $pedido->save();

        return back()->with('success', "Pedido #{$pedido->id} pagado con " . ucfirst($request->metodo_pago) . ".")
                     ->with('imprimir_ticket', $pedido->id);
    }
    
    public function eliminarVenta(Request $request, Pedido $pedido)
    {
        $request->validate([
            'motivo' => 'required|string|max:500',
        ]);
        
        $pedido->eliminado = true;
        $pedido->eliminado_at = now();
        $pedido->motivo_eliminacion = $request->motivo;
        $pedido->save();

        return back()->with('success', "Venta #{$pedido->id} eliminada correctamente.")
                     ->with('imprimir_ticket_eliminado', $pedido->id);
    }
}
