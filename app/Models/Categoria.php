<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    /**
     * Define la tabla asociada al modelo.
     * Por convención, Laravel asume 'categorias', pero es bueno explicitar.
     *
     * @var string
     */
    protected $table = 'categorias'; 

    /**
     * Los atributos que son asignables masivamente (Mass Assignable).
     * Asegúrate de incluir 'nombre' y 'slug' si los usas al crear categorías.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        // Agrega cualquier otro campo que tu tabla 'categorias' tenga
    ];

    /**
     * Define la relación "uno a muchos" con el modelo Producto.
     * Una Categoría tiene muchos Productos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productos()
    {
        // Asume que la tabla 'productos' tiene una columna 'categoria_id'
        // que es la clave foránea que apunta a esta categoría.
        return $this->hasMany(Producto::class);
    }
}