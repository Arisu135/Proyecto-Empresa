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
     * * @var array<int, string>
     */
    protected $fillable = [
        'pedido_id', 
        'producto_id', 
        'nombre_producto', 
        'cantidad',
        'precio_unitario',
        'subtotal',
        'opciones_personalizadas', // ⬅️ ¡ESTA ES LA LÍNEA CRÍTICA QUE FALTABA!
    ];

    /**
     * Los atributos que deben ser casteados.
     * Opcional: Esto asegura que 'opciones_personalizadas' siempre se trate como JSON en el modelo.
     */
    protected $casts = [
        'opciones_personalizadas' => 'array',
    ];


    /**
     * Un detalle de pedido pertenece a un solo pedido.
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
    
    /**
     * Un detalle de pedido se refiere a un producto.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}