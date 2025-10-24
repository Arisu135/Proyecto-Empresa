<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria; 
use App\Models\Pedido;
use App\Models\PedidoDetalle; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; 
use Illuminate\Database\Eloquent\Collection; 

class CatalogoController extends Controller
{
    /**
     * Muestra la vista de bienvenida del Kiosco.
     */
    public function index()
    {
        return view('catalogo.index'); 
    }

    /**
     * Muestra el menú de categorías, cargando las categorías de la DB.
     */
    public function productos(Request $request)
    {
        // Si viene del index, guardamos el tipo de pedido en sesión
        if ($request->has('tipo_pedido')) {
            Session::put('tipo_pedido', $request->get('tipo_pedido'));
        }
        
        $categorias = Categoria::orderBy('nombre', 'asc')->get(); 

        return view('productos.menu', compact('categorias'));
    }
    
    /**
     * Muestra la vista de productos de una categoría específica.
     */
    public function mostrarProductosPorCategoria($categoria_slug)
    {
        $categoria = Categoria::where('slug', $categoria_slug)->firstOrFail();
        $productos = $categoria->productos()->orderBy('nombre')->get(); 
        
        return view('productos.categoria', compact('categoria', 'productos'));
    }
    
    /**
     * Muestra la vista de detalle y personalización de un producto.
     */
    public function mostrarDetalle(Producto $producto)
    {
        $producto = Producto::with('categoria')->findOrFail($producto->id); 
        
        return view('catalogo.detalle_pedido', compact('producto')); 
    }

    /**
     * Agrega un producto (posiblemente personalizado) al carrito de la sesión.
     */
    public function agregarAlCarrito(Request $request, Producto $producto)
    {
        $cantidadAñadir = (int) $request->input('cantidad', 1);
        $precioUnitarioFinal = (float) $request->input('precio_final', $producto->precio); 
        $opcionesSeleccionadasJson = $request->input('opciones_seleccionadas');
        
        if ($cantidadAñadir <= 0) {
            return back()->with('error', 'La cantidad debe ser al menos 1.');
        }

        $carrito = Session::get('carrito', []);
        
        // Generar una clave única basada en ID y opciones
        $opciones_hash = hash('sha256', $opcionesSeleccionadasJson ?? '');
        $itemIdKey = $producto->id . '_' . $opciones_hash;
        
        // Procesar las opciones para un nombre legible en el carrito
        $opcionesArray = json_decode($opcionesSeleccionadasJson, true);
        $opcionesStr = '';
        if (!empty($opcionesArray)) {
            $nombresOpciones = array_column($opcionesArray, 'value');
            $opcionesStr = ' (' . implode(', ', $nombresOpciones) . ')';
        }
        
        $nombreProductoCarrito = $producto->nombre . $opcionesStr;

        if (isset($carrito[$itemIdKey])) {
            $carrito[$itemIdKey]['cantidad'] += $cantidadAñadir;
        } else {
            $carrito[$itemIdKey] = [
                'id' => $producto->id,
                'nombre' => $nombreProductoCarrito,
                'precio' => $precioUnitarioFinal, 
                'cantidad' => $cantidadAñadir,
                'opciones' => $opcionesArray, 
            ];
        }

        $carrito[$itemIdKey]['subtotal'] = $carrito[$itemIdKey]['precio'] * $carrito[$itemIdKey]['cantidad'];

        Session::put('carrito', $carrito);
        
        return redirect('/pedido/resumen')->with('success', $cantidadAñadir . 'x ' . $producto->nombre . ' agregado al pedido.');
    }

    /**
     * Resta una unidad de un ítem del carrito (o lo elimina si llega a cero).
     */
    public function restarDelCarrito(Request $request, $itemKey)
    {
        $carrito = Session::get('carrito', []);
        $message = 'Pedido actualizado.';

        if (isset($carrito[$itemKey])) {
            $carrito[$itemKey]['cantidad']--;

            if ($carrito[$itemKey]['cantidad'] <= 0) {
                unset($carrito[$itemKey]);
                $message = 'Producto eliminado del pedido.';
            } else {
                $carrito[$itemKey]['subtotal'] = $carrito[$itemKey]['precio'] * $carrito[$itemKey]['cantidad'];
                $message = 'Cantidad de producto actualizada.';
            }

            Session::put('carrito', $carrito);
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Muestra el resumen del carrito antes de finalizar la compra.
     */
    public function verResumen()
    {
        $carrito = Session::get('carrito', []);
        
        $total = array_sum(array_column($carrito, 'subtotal'));
        $tipoPedido = Session::get('tipo_pedido');

        return view('carrito.resumen', [ 
            'carrito_items' => $carrito,
            'total' => $total,
            'tipoPedido' => $tipoPedido, 
        ]);
    }

    /**
     * Limpia el carrito y reinicia el proceso del pedido.
     */
    public function empezarDeNuevo()
    {
        Session::forget(['carrito', 'tipo_pedido']); 
        return redirect()->route('catalogo.index')->with('warning', 'Se ha iniciado un nuevo pedido.');
    }

    /**
     * Elimina un ítem completo del carrito.
     */
    public function eliminarItem($itemKey)
    {
        $carrito = Session::get('carrito', []);
        
        if (isset($carrito[$itemKey])) {
            unset($carrito[$itemKey]);
            Session::put('carrito', $carrito);
        }
        
        return back()->with('success', 'Producto eliminado del pedido.');
    }
    
    /**
     * Procesa la finalización del pedido y lo guarda en la base de datos.
     */
    public function finalizarPedido(Request $request)
    {
        $tipoPedido = Session::get('tipo_pedido');
        $carrito = Session::get('carrito', []);
        
        // 🚨 MODIFICACIÓN CLAVE: Si falta el tipo de pedido, redirigimos al inicio
        // para forzar la selección, evitando el 'back()' confuso.
        if (!$tipoPedido) {
            return redirect()->route('catalogo.index')->with('error', 'Por favor, selecciona el tipo de pedido (Mesa/Llevar) para comenzar.');
        }

        if (empty($carrito)) {
            return redirect()->route('catalogo.index')->with('error', 'No puedes finalizar un pedido sin productos.');
        }
        
        $total = array_sum(array_column($carrito, 'subtotal'));

        DB::beginTransaction();

        try {
            $pedido = Pedido::create([
                'tipo_pedido' => $tipoPedido, 
                'total' => $total,
                'estado' => 'Pendiente', 
            ]);

            foreach ($carrito as $itemKey => $item) { 
                PedidoDetalle::create([
                    'pedido_id'       => $pedido->id,
                    'producto_id'     => $item['id'], 
                    'nombre_producto' => $item['nombre'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'], 
                    'subtotal'        => $item['subtotal'],
                    'opciones_personalizadas' => json_encode($item['opciones'] ?? []), 
                ]);
            }

            DB::commit();

            Session::forget(['carrito', 'tipo_pedido']); 

            // Redirige al cliente a la página de confirmación con el ID del pedido.
            return redirect()->route('pedido.confirmacion', $pedido->id)->with('success', '¡Tu pedido ha sido enviado con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Error al finalizar el pedido: " . $e->getMessage());

            // 🚨 MODIFICACIÓN: Redirigimos al resumen con el error para que sea visible.
            return redirect()->route('pedido.resumen')->with('error', 'Hubo un error al procesar tu pedido. Por favor, inténtalo de nuevo. Contacta al personal. (Error: ' . $e->getMessage() . ')');
        }
    }

    /**
     * Muestra la vista de confirmación del pedido específico.
     */
    public function confirmacionPedido($id)
    {
        // Si falla aquí, significa que la vista 'pedidos.confirmacion' no existe o la relación 'detalles' está mal.
        $pedido = Pedido::with('detalles')->findOrFail($id);
        
        return view('pedidos.confirmacion', [
            'pedido' => $pedido
        ]);
    }
    
    /**
     * Muestra la vista de agradecimiento (método de respaldo, no usado en el flujo principal).
     */
    public function agradecimiento()
    {
        $mensaje = Session::get('success', 'Gracias por tu compra.'); 
        return view('catalogo.agradecimiento', compact('mensaje'));
    }

    // =======================================================
    // MÉTODOS DE ADMINISTRACIÓN (COCINA)
    // =======================================================

    /**
     * Muestra el panel de gestión de pedidos para la cocina.
     */
    public function gestion()
    {
        $pedidos = Pedido::with('detalles')
                            ->where('estado', '!=', 'Entregado') 
                            ->orderByRaw("CASE estado WHEN 'Pendiente' THEN 1 WHEN 'Preparando' THEN 2 WHEN 'Listo' THEN 3 ELSE 4 END")
                            ->orderBy('created_at', 'asc')
                            ->get();
                             
        return view('admin.gestion_pedidos', compact('pedidos'));
    }

    /**
     * Actualiza el estado de un pedido (usado por la cocina).
     */
    public function actualizarEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,Preparando,Listo,Entregado,Cancelado',
        ]);
        
        $pedido->estado = $request->estado;
        $pedido->save();

        return back()->with('success', "Estado del pedido #{$pedido->id} actualizado a {$pedido->estado}.");
    }
}