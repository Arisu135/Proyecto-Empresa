<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- IMPORTACIÓN AÑADIDA

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 🛑 CORRECCIÓN CRÍTICA PARA HEROKU (HTTPS) 🛑
        // Esto fuerza a Laravel a generar URLs y rutas con HTTPS, 
        // eliminando la advertencia de seguridad que bloquea el formulario POST.
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
