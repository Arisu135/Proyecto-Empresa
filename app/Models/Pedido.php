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
        'nombre_cliente', // Usado para guardar el NÚMERO DE MESA
        'direccion',      // Usado para guardar las Notas/Instrucciones
        'total',
        'estado',         // Estado inicial: Pendiente
    ];
}
