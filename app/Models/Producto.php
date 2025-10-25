<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;

class Producto extends Model
{
    use HasFactory;
    
    // Indica la clave foránea si no es la convención predeterminada de Laravel (categoria_id)
    // Es bueno dejarlo explícito para evitar fallos.
    protected $table = 'productos'; 
    
    protected $fillable = [
        'nombre', 
        'descripcion', 
        'precio', 
        'categoria_id', 
        'imagen_nombre', 
        'opciones',
    ];

    // Define los atributos que deben ser convertidos a tipos nativos (como JSON para 'opciones')
    protected $casts = [
        'opciones' => 'array', 
    ];

    /**
     * Define la relación: Un producto pertenece a una Categoría.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria()
    {
        // Se especifica la clave foránea 'categoria_id' para total seguridad.
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}

