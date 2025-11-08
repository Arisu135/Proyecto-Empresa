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
            ->orderBy('created_at', 'asc')
            ->get();

        return view('caja.index', compact('pedidos'));
    }

    public function marcarPagado(Request $request, Pedido $pedido)
    {
        $pedido->pagado = true;
        $pedido->save();

        return back()->with('success', "Pedido #{$pedido->id} marcado como pagado.");
    }
}
