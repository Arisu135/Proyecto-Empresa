<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PedidoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * Este método gestiona la finalización de la compra.
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN DE DATOS
        try {
            $validated = $request->validate([
                'nombre_cliente' => ['required', 'string', 'max:255'],
                'direccion' => ['required', 'string', 'max:500'],
                'tipo_pedido' => ['required', 'string', 'in:delivery,takeaway'], // Asegúrate de que 'tipo_pedido' exista en la tabla 'pedidos'
                'total_compra' => ['required', 'numeric', 'min:0.01'],
                // Agrega más validaciones según los campos de tu formulario
            ]);
        } catch (ValidationException $e) {
            // Si la validación falla, regresa con los errores.
            return back()->withErrors($e->errors())->withInput();
        }

        // 2. RECUPERAR DATOS ESENCIALES
        $carrito = session('carrito', []); // Asumimos que los ítems del carrito están en la sesión
        
        if (empty($carrito)) {
            return back()->with('error', 'Tu carrito está vacío. No se puede realizar el pedido.');
        }

        // Usamos una transacción para asegurar que si falla el guardado de un detalle,
        // no se guarde nada del pedido principal.
        DB::beginTransaction();

        try {
            // 3. CREAR EL PEDIDO PRINCIPAL (Tabla 'pedidos')
            $pedido = Pedido::create([
                'nombre_cliente' => $validated['nombre_cliente'],
                'direccion' => $validated['direccion'],
                'total' => $validated['total_compra'],
                'estado' => 'Pendiente', 
                'tipo_pedido' => $validated['tipo_pedido'], // Se añade 'tipo_pedido'
            ]);

            // 4. CREAR LOS DETALLES DEL PEDIDO (Tabla 'pedido_detalles')
            foreach ($carrito as $item) {
                
                // Aseguramos que las opciones personalizadas se guarden como texto (JSON string)
                $opcionesJson = json_encode($item['opciones'] ?? null); 
                
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['id'], // Asume que cada ítem tiene un ID
                    'nombre_producto' => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $item['precio'] * $item['cantidad'],
                    'opciones_personalizadas' => $opcionesJson, // Guardamos la columna nueva
                ]);
            }

            DB::commit();

            // Limpiar el carrito después de un pedido exitoso
            $request->session()->forget('carrito'); 

            // 5. REDIRECCIÓN DE ÉXITO
            // Esto es lo que NO estaba pasando antes.
            return redirect()->route('confirmacion', ['id' => $pedido->id])
                             ->with('success', '¡Tu pedido ha sido realizado con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // 6. MANEJO DE ERRORES (En Heroku, esto es crucial)
            // Esto te ayudará a ver qué excepción está ocurriendo si vuelve a fallar.
            \Log::error('Fallo al procesar el pedido: ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());

            // Si el proceso falla, redirecciona al carrito o a la página anterior con un mensaje de error.
            return back()->with('error', 'Hubo un error al procesar tu pedido. Inténtalo de nuevo o contacta a soporte. Detalles: ' . $e->getMessage());
        }
    }


    /**
     * Muestra la página de confirmación del pedido.
     */
    public function confirmacionPedido($id)
    {
        // Esta función debe existir y cargar la vista de confirmación
        $pedido = Pedido::with('detalles')->findOrFail($id);
        
        // Aquí debes retornar tu vista de confirmación, por ejemplo:
        // return view('pedidos.confirmacion', compact('pedido'));
        
        // Coloco un mensaje simple para que sepas que llegaste aquí si no tienes vista
        return "<h1>Pedido #{$id} Confirmado</h1><p>Gracias por tu compra.</p>";
    }
    
    // El resto de los métodos se dejan vacíos o con su lógica original
    public function index() { /* ... */ }
    public function create() { /* ... */ }
    public function show(Pedido $pedido) { /* ... */ }
    public function edit(Pedido $pedido) { /* ... */ }
    public function update(Request $request, Pedido $pedido) { /* ... */ }
    public function destroy(Pedido $pedido) { /* ... */ }
}

