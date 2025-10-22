<?php

namespace App\Models; // Asume que tus modelos están en el namespace App\Models

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto; // Importamos la clase Producto

class Categoria extends Model
{
    use HasFactory;

    /**
     * Define la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'categorias'; 

    /**
     * Los atributos que son asignables masivamente.
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        // Si tu tabla categorias tiene una columna para la imagen de la categoría, inclúyela aquí:
        // 'imagen_nombre', 
    ];

    /**
     * Define la relación "uno a muchos" con el modelo Producto.
     * Esto busca productos que tengan el 'categoria_id' igual al ID de esta categoría.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productos()
    {
        // Se usa la clase 'Producto::class' importada, y se especifica la llave foránea.
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}