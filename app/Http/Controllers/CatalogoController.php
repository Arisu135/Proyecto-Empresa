<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria; 
use App\Models\Pedido;
use App\Models\PedidoDetalle; // Asumiendo que esta es tu tabla de ítems de pedido
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; 
use Illuminate\Database\Eloquent\Collection; // Mantenemos esta importación

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
        
        // Carga las categorías reales de la base de datos
        $categorias = Categoria::orderBy('nombre', 'asc')->get(); 

        // Retorna la vista pasando la colección de categorías
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
                'opciones' => $opcionesArray, // Esto es el array que necesitamos guardar en DB
            ];
        }

        $carrito[$itemIdKey]['subtotal'] = $carrito[$itemIdKey]['precio'] * $carrito[$itemIdKey]['cantidad'];

        Session::put('carrito', $carrito);
        
        // Redirige a la vista de resumen
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
        
        // Cálculo del total general del pedido
        $total = array_sum(array_column($carrito, 'subtotal'));
        $tipoPedido = Session::get('tipo_pedido');

        // Apunta a la vista de resumen
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
     * ✅ LÓGICA DE REDIRECCIÓN CORREGIDA
     */
    public function finalizarPedido(Request $request)
    {
        $tipoPedido = Session::get('tipo_pedido');
        $carrito = Session::get('carrito', []);
        
        if (!$tipoPedido) {
            return back()->with('error', 'Por favor, selecciona el tipo de pedido antes de finalizar.');
        }

        if (empty($carrito)) {
            return redirect()->route('catalogo.index')->with('error', 'No puedes finalizar un pedido sin productos.');
        }
        
        $total = array_sum(array_column($carrito, 'subtotal'));

        DB::beginTransaction();

        try {
            // Asumo que el campo 'nombre_cliente' no es requerido por ahora, se genera el Pedido.
            $pedido = Pedido::create([
                'tipo_pedido' => $tipoPedido, 
                'total' => $total,
                'estado' => 'Pendiente', 
                // Si la columna existe, podrías querer guardar un nombre por defecto
                // 'nombre_cliente' => 'Cliente Kiosco',
            ]);

            foreach ($carrito as $itemKey => $item) { 
                // Usamos PedidoDetalle, el modelo que definiste
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

            // 🎯 CAMBIO CLAVE: Redirige a la nueva ruta dinámica de confirmación.
            return redirect()->route('pedido.confirmacion', $pedido->id)->with('success', '¡Tu pedido ha sido enviado con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Error al finalizar el pedido: " . $e->getMessage());

            return back()->with('error', 'Hubo un error al procesar tu pedido. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Muestra la vista de confirmación del pedido específico (reemplaza agradecimiento).
     * ✅ MÉTODO NUEVO AÑADIDO
     */
    public function confirmacionPedido($id)
    {
        // 1. Buscamos el pedido con sus detalles
        // Asumiendo que la relación se llama 'detalles' en el modelo Pedido
        $pedido = Pedido::with('detalles')->findOrFail($id);
        
        // 2. Cargamos la nueva vista que creaste
        return view('pedidos.confirmacion', [
            'pedido' => $pedido
        ]);
    }
    
    /**
     * NOTA: Este método (agradecimiento) ya no es usado por el flujo principal,
     * pero lo mantenemos por si lo necesitas como ruta de respaldo.
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
        // Carga los pedidos ordenados por estado (Pendiente primero) y luego por fecha
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