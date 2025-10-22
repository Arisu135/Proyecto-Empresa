<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;

class Producto extends Model
{
    use HasFactory;
    
    // Si tu tabla no es 'productos', descomenta y ajusta:
    // protected $table = 'nombre_de_tu_tabla'; 
    
    // Si usas Asignación Masiva, define los campos rellenables:
    // protected $fillable = ['nombre', 'descripcion', 'precio', 'categoria_id', 'imagen_nombre', 'opciones'];

    // Define los atributos que deben ser convertidos a tipos nativos (como JSON para 'opciones')
    protected $casts = [
        'opciones' => 'array', 
    ];

    /**
     * Define la relación: Un producto pertenece a una Categoría.
     */
    public function categoria()
    {
        // Esta línea es CRÍTICA: Asegúrate de que use el nombre de clase correcto (Categoria::class)
        return $this->belongsTo(Categoria::class);
    }
}