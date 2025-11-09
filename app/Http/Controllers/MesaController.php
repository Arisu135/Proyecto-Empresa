<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function index()
    {
        // Mostrar todos los pedidos que NO estén entregados (Cocina completa)
        $pedidos = Pedido::with('detalles')
            ->where('estado', '!=', 'Entregado')
            ->orderByRaw("CASE estado WHEN 'Pendiente' THEN 1 WHEN 'En Preparación' THEN 2 WHEN 'Listo' THEN 3 ELSE 4 END")
            ->orderBy('created_at', 'asc')
            ->get();

        return view('mesas.index', compact('pedidos'));
    }
    
    public function actualizarEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,En Preparación,Listo,Entregado,Cancelado',
            'motivo_cancelacion' => 'required_if:estado,Cancelado|string|max:500',
        ]);
        
        $pedido->estado = $request->estado;
        
        if ($request->estado === 'Cancelado') {
            $pedido->eliminado = true;
            $pedido->eliminado_at = now();
            $pedido->motivo_eliminacion = $request->motivo_cancelacion;
        }
        
        $pedido->save();

        return back()->with('success', "Estado del pedido #{$pedido->id} actualizado a {$pedido->estado}.");
    }

    public function marcarEntregado(Request $request, Pedido $pedido)
    {
        $pedido->estado = 'Entregado';
        $pedido->save();

        return back()->with('success', "Pedido #{$pedido->id} marcado como entregado.");
    }
}
