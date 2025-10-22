<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    use HasFactory;

    /**
     * Define la tabla asociada al modelo (Laravel asume 'pedido_detalles').
     *
     * @var string
     */
    protected $table = 'pedido_detalles';

    /**
     * Los atributos que son asignables masivamente.
     * Estos campos coinciden con los datos que insertas en el controlador.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pedido_id',        // Clave foránea al Pedido
        'producto_id',      // Clave foránea al Producto
        'nombre_producto',  // Nombre del producto con personalización (e.g., "Capuccino (Sin Hielo)")
        'cantidad',
        'precio_unitario',
        'subtotal',
        // Puedes agregar campos para guardar las opciones de personalización aquí si los tienes en la tabla
    ];

    /**
     * Un detalle de pedido pertenece a un solo pedido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
    
    /**
     * Un detalle de pedido se refiere a un producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}