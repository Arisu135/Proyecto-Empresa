<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('detalles')
            ->whereIn('estado', ['Listo', 'Entregado'])
            ->where('pagado', false)
            ->where('eliminado', false)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('caja.index', compact('pedidos'));
    }

    public function marcarPagado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,yape,mixto',
        ]);
        
        $pedido->pagado = true;
        $pedido->metodo_pago = $request->metodo_pago;
        $pedido->impreso = false;
        $pedido->save();

        return back()->with('success', "Pedido #{$pedido->id} pagado con " . ucfirst($request->metodo_pago) . ".");
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

    public function agregarProductos(Pedido $pedido)
    {
        session(['pedido_actual' => $pedido->id]);
        session(['tipo_pedido' => $pedido->tipo_pedido ?? 'Para Aqui']);
        return redirect()->route('productos.menu');
    }

    public function agregarProductosPost(Request $request, Pedido $pedido)
    {
        $carrito = session('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('caja.index')->with('error', 'No hay productos para agregar.');
        }

        $totalNuevo = 0;
        foreach ($carrito as $item) {
            $opcionesJson = json_encode($item['opciones'] ?? null);
            
            \App\Models\PedidoDetalle::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item['id'],
                'nombre_producto' => $item['nombre'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio'],
                'subtotal' => $item['precio'] * $item['cantidad'],
                'opciones_personalizadas' => $opcionesJson,
            ]);
            
            $totalNuevo += $item['precio'] * $item['cantidad'];
        }

        $pedido->total += $totalNuevo;
        $pedido->save();

        session()->forget(['carrito', 'pedido_actual']);

        return redirect()->route('caja.index')->with('success', "Productos agregados al pedido #{$pedido->id}");
    }
}
