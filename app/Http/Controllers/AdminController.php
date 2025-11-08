<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Muestra el panel principal de administración.
     */
    public function panel()
    {
        // Esta vista ya existe, solo centralizamos la lógica aquí.
        return view('admin.panel');
    }

    /**
     * Muestra la página de selección de roles (portal de acceso).
     */
    public function acceso()
    {
        return view('admin.acceso');
    }
}
