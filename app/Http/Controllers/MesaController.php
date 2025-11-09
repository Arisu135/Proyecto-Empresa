<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function index()
    {
        // Mostrar todos los pedidos que NO estén entregados, listos, pagados o eliminados
        $pedidos = Pedido::with('detalles')
            ->where('estado', '!=', 'Entregado')
            ->where('estado', '!=', 'Listo')
            ->where('estado', '!=', 'Cancelado')
            ->where('pagado', false)
            ->where('eliminado', false)
            ->orderByRaw("CASE estado WHEN 'Pendiente' THEN 1 WHEN 'En Preparación' THEN 2 ELSE 3 END")
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

    public function eliminarTodo()
    {
        $pedidos = Pedido::where('estado', '!=', 'Entregado')
            ->where('estado', '!=', 'Listo')
            ->where('pagado', false)
            ->where('eliminado', false)
            ->get();
        $count = $pedidos->count();
        
        foreach ($pedidos as $pedido) {
            $pedido->estado = 'Cancelado';
            $pedido->eliminado = true;
            $pedido->eliminado_at = now();
            $pedido->motivo_eliminacion = 'Eliminación masiva desde cocina';
            $pedido->save();
        }

        return redirect()->route('mesas.index')->with('success', "Se eliminaron {$count} pedidos.");
    }

    public function historial()
    {
        $pedidos = Pedido::with('detalles')
            ->where('eliminado', true)
            ->where('motivo_eliminacion', 'LIKE', '%cocina%')
            ->orderBy('eliminado_at', 'desc')
            ->get();
        
        $totalPerdido = $pedidos->sum('total');

        return view('mesas.historial_eliminadas', compact('pedidos', 'totalPerdido'));
    }

    public function limpiarHistorial($tipo)
    {
        $query = Pedido::where('eliminado', true)
            ->where('motivo_eliminacion', 'LIKE', '%cocina%');
        
        if ($tipo === 'hoy') {
            $query->whereDate('eliminado_at', now()->toDateString());
        }
        
        $count = $query->count();
        $query->forceDelete();

        return redirect()->route('mesas.historial')->with('success', "Se eliminaron {$count} registros del historial.");
    }
}
