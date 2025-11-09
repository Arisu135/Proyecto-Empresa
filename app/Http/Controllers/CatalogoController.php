<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria; 
use App\Models\Pedido;
use App\Models\PedidoDetalle; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; 
use Illuminate\Database\Eloquent\Collection; 
use Illuminate\Validation\ValidationException; // Aseguramos el uso de ValidationException

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
        // Asegúrate de obtener el precio unitario del producto o el precio final calculado si hay opciones.
        $precioUnitarioFinal = (float) $request->input('precio_final');
        
        // Si precio_final está vacío o es 0, usar el precio base del producto
        if (empty($precioUnitarioFinal) || $precioUnitarioFinal <= 0) {
            $precioUnitarioFinal = (float) $producto->precio;
        }
        
        $opcionesSeleccionadasJson = $request->input('opciones_seleccionadas');
        
        Log::info('Agregando al carrito', [
            'producto_id' => $producto->id,
            'precio_producto' => $producto->precio,
            'precio_final_recibido' => $request->input('precio_final'),
            'precio_usado' => $precioUnitarioFinal,
            'cantidad' => $cantidadAñadir
        ]);
        
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

        return view('carrito.carrito_resumen', [ 
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
     * ESTE ES EL MÉTODO CLAVE.
     */
    public function finalizarPedido(Request $request)
    {
        // Registro inicial para depuración: captura inputs y estado de sesión
        Log::info('CatalogoController::finalizarPedido llamado', [
            'input' => $request->all(),
            'session_tipo_pedido' => Session::get('tipo_pedido'),
            'session_carrito_count' => count(Session::get('carrito', [])),
        ]);
        // 1. Validar el formulario de envío (nombre y dirección)
        try {
            $validated = $request->validate([
                // Nota: Los campos 'nombre_cliente' y 'direccion' deben estar en tu formulario de resumen.
                'nombre_cliente' => ['nullable', 'string', 'max:255'],
                'direccion' => ['nullable', 'string', 'max:500'], 
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $tipoPedido = Session::get('tipo_pedido');
        $carrito = Session::get('carrito', []);
        
        // Comprobaciones de seguridad
        if (!$tipoPedido) {
            return redirect()->route('catalogo.index')->with('error', 'Por favor, selecciona el tipo de pedido (Mesa/Llevar) para comenzar.');
        }

        if (empty($carrito)) {
            return redirect()->route('catalogo.index')->with('error', 'No puedes finalizar un pedido sin productos.');
        }
        
        $total = array_sum(array_column($carrito, 'subtotal'));

        DB::beginTransaction();

        try {
            // A. Crear el Pedido Principal
            Log::info('Creando Pedido en la DB', ['tipo_pedido' => $tipoPedido, 'total' => $total, 'nombre_cliente' => $validated['nombre_cliente'] ?? null]);

            $pedido = Pedido::create([
                'tipo_pedido' => $tipoPedido, 
                'nombre_cliente' => $validated['nombre_cliente'] ?? 'Cliente Kiosco',
                'direccion' => $validated['direccion'] ?? ($tipoPedido == 'takeaway' ? 'Para llevar' : 'En el lugar'),
                'numero_mesa' => $request->input('numero_mesa'),
                'total' => $total,
                'estado' => 'Pendiente',
                'pagado' => false,
            ]);

            // B. Crear los Detalles del Pedido
            foreach ($carrito as $itemKey => $item) { 
                
                // CRUCIAL: Convertir el array de opciones a JSON string para la columna TEXT
                $opcionesJson = json_encode($item['opciones'] ?? []); 
                
                Log::info('Creando PedidoDetalle', ['pedido_id' => $pedido->id, 'producto_id' => $item['id'], 'cantidad' => $item['cantidad']]);

                PedidoDetalle::create([
                    'pedido_id'               => $pedido->id,
                    'producto_id'             => $item['id'], 
                    'nombre_producto'         => $item['nombre'],
                    'cantidad'                => $item['cantidad'],
                    'precio_unitario'         => $item['precio'], 
                    'subtotal'                => $item['subtotal'],
                    'opciones_personalizadas' => $opcionesJson, // ¡Aquí se usa la columna que migramos!
                ]);
            }

            DB::commit();

            // 2. Limpiar la sesión
            Session::forget(['carrito', 'tipo_pedido']); 

            // 3. Redirección de Éxito
            return redirect()->route('pedido.confirmacion', $pedido->id)->with('success', '¡Tu pedido ha sido enviado con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log de error detallado para Heroku
            Log::error("Error al finalizar el pedido: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());

            // Redirigimos al resumen con el error para que sea visible.
            return redirect()->route('pedido.resumen')->with('error', 'Hubo un error al procesar tu pedido. Por favor, inténtalo de nuevo. Detalles: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la vista de confirmación del pedido específico.
     */
    public function confirmacionPedido($id)
    {
        $pedido = Pedido::with('detalles')->findOrFail($id);
        
        return view('pedidos.confirmacion', [
            'pedido' => $pedido
        ]);
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
     * Muestra el historial de ventas.
     * Permite filtrar por fecha (YYYY-MM-DD) o ver todas las ventas.
     */
    public function ventas(Request $request)
    {
        $filter = $request->query('filter', 'hoy');
        $categoriaId = $request->query('categoria');
        
        // Base query
        $query = Pedido::with(['detalles.producto.categoria']);
        
        // Filtro de fecha
        if ($filter === 'todos') {
            $titulo = 'Historial de Ventas - Todas las Fechas';
        } elseif ($filter === 'hoy') {
            $query->whereDate('created_at', now()->toDateString());
            $titulo = 'Ventas de Hoy (' . now()->format('d/m/Y') . ')';
        } else {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $filter)->startOfDay();
                $query->whereDate('created_at', $date->toDateString());
                $titulo = 'Ventas del ' . $date->format('d/m/Y');
            } catch (\Exception $e) {
                $query->whereRaw('1 = 0'); // No results
                $titulo = 'Ventas - Fecha inválida';
            }
        }
        
        $pedidos = $query->orderBy('created_at', 'desc')->get();
        
        // Filtrar por categoría si se especifica
        if ($categoriaId) {
            $pedidos = $pedidos->filter(function($pedido) use ($categoriaId) {
                return $pedido->detalles->contains(function($detalle) use ($categoriaId) {
                    return $detalle->producto && $detalle->producto->categoria_id == $categoriaId;
                });
            });
            
            $categoria = Categoria::find($categoriaId);
            if ($categoria) {
                $titulo .= ' - Categoría: ' . $categoria->nombre;
            }
        }
        
        // Calcular estadísticas
        $totalVentas = $pedidos->sum('total');
        $totalPedidos = $pedidos->count();
        
        // Estadísticas por categoría
        $ventasPorCategoria = [];
        foreach ($pedidos as $pedido) {
            foreach ($pedido->detalles as $detalle) {
                if ($detalle->producto && $detalle->producto->categoria) {
                    $catNombre = $detalle->producto->categoria->nombre;
                    if (!isset($ventasPorCategoria[$catNombre])) {
                        $ventasPorCategoria[$catNombre] = [
                            'cantidad' => 0,
                            'total' => 0
                        ];
                    }
                    $ventasPorCategoria[$catNombre]['cantidad'] += $detalle->cantidad;
                    $ventasPorCategoria[$catNombre]['total'] += $detalle->subtotal;
                }
            }
        }
        
        // Obtener todas las categorías para el filtro
        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.ventas', [
            'pedidos' => $pedidos,
            'titulo' => $titulo,
            'totalVentas' => $totalVentas,
            'totalPedidos' => $totalPedidos,
            'ventasPorCategoria' => $ventasPorCategoria,
            'categorias' => $categorias,
            'filter' => $filter,
            'categoriaId' => $categoriaId,
        ]);
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
    
    public function eliminarVentaAdmin(Request $request, Pedido $pedido)
    {
        $request->validate(['motivo' => 'required|string|max:500']);
        
        $pedido->eliminado = true;
        $pedido->eliminado_at = now();
        $pedido->motivo_eliminacion = $request->motivo;
        $pedido->estado = 'Cancelado';
        $pedido->save();

        return back()->with('success', "Venta #{$pedido->id} eliminada correctamente.");
    }

    public function ventasEliminadas(Request $request)
    {
        $filter = $request->query('filter', 'todos');
        
        $query = Pedido::with('detalles')
            ->where(function($q) {
                $q->where('eliminado', true)
                  ->orWhere('estado', 'Cancelado');
            });
        
        if ($filter === 'hoy') {
            $query->whereDate('created_at', now()->toDateString());
            $titulo = 'Ventas Eliminadas/Canceladas - Hoy';
        } else {
            $titulo = 'Historial de Ventas Eliminadas/Canceladas';
        }
        
        $pedidos = $query->orderBy('created_at', 'desc')->get();
        $totalPerdido = $pedidos->sum('total');

        return view('admin.ventas_eliminadas', [
            'pedidos' => $pedidos,
            'titulo' => $titulo,
            'totalPerdido' => $totalPerdido,
            'filter' => $filter,
        ]);
    }
}


