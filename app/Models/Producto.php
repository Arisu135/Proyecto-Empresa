<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;

class Producto extends Model
{
    use HasFactory;
    

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
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
