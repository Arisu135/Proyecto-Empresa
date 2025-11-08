<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('detalles')
            ->where('estado', 'Listo')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('mesas.index', compact('pedidos'));
    }

    public function marcarEntregado(Request $request, Pedido $pedido)
    {
        $pedido->estado = 'Entregado';
        $pedido->save();

        return back()->with('success', "Pedido #{$pedido->id} marcado como entregado.");
    }
}
