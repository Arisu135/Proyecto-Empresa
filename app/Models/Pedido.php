<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     * Esta propiedad soluciona el error MassAssignmentException.
     */
    protected $fillable = [
        'nombre_cliente', 
        'direccion',      
        'total',
        'estado',         
        'tipo_pedido', // ⬅️ ¡¡ESTA ES LA COLUMNA QUE FALTABA!!
    ];

    /**
     * Define la relación con los detalles (ítems) del pedido.
     * Esto es necesario para el método confirmacionPedido($id).
     */
    public function detalles()
    {
        // Asumiendo que tu modelo de detalle se llama 'PedidoDetalle'
        return $this->hasMany(PedidoDetalle::class);
    }
}

