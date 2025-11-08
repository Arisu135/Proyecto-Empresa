<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.panel');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $password = $request->input('password');
        $correctPassword = env('ADMIN_PASSWORD', 'admin123');

        if ($password === $correctPassword) {
            session(['admin_authenticated' => true]);
            return redirect()->route('admin.panel');
        }

        return back()->with('error', 'Contraseña incorrecta');
    }

    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect()->route('catalogo.index');
    }
}
