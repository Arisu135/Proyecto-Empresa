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
        'tipo_pedido',
        'numero_mesa',
        'pagado',
        'metodo_pago',
        'eliminado',
        'eliminado_at',
        'motivo_eliminacion',
        'impreso',
        'impreso_at',
    ];

    protected $casts = [
        'pagado' => 'boolean',
        'eliminado' => 'boolean',
        'eliminado_at' => 'datetime',
        'impreso' => 'boolean',
        'impreso_at' => 'datetime',
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

