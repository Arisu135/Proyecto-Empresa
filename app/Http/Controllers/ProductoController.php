<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; // Necesario para obtener la ruta actual

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource (Menú o CRUD).
     */
    public function index()
    {
        $productos = Producto::all();
        
        // --- LÓGICA CLAVE DE SEPARACIÓN ---
        // 1. Obtener el nombre de la ruta actual (ej: 'productos.index' o 'catalogo.menu')
        $currentRouteName = Route::currentRouteName();

        // 2. Decidir qué vista cargar basándose en el nombre de la ruta
        if ($currentRouteName === 'catalogo.menu') {
            // Si la ruta es /menu (para el cliente), cargamos la vista de tarjetas bonitas
            return view('catalogo.productos', ['productos' => $productos]);
        } else {
            // Si la ruta es /productos (para el admin), cargamos la vista simple de gestión
            return view('productos.index', ['productos' => $productos]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Crear un nuevo producto en la base de datos
        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria' => $request->categoria,
        ]);
        
        // 2. Redirigir al usuario de vuelta a la lista de productos
        return redirect('/productos');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        // 1. Validar y actualizar los campos del producto
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'categoria' => $request->categoria,
        ]);
        
        // 2. Redirigir al usuario de vuelta a la lista de productos
        return redirect('/productos');
    }

    public function destroy(Producto $producto)
    {
        // 1. Eliminar el producto de la base de datos
        $producto->delete();
        
        // 2. Redirigir al usuario de vuelta a la lista de productos
        return redirect('/productos');
    }
}