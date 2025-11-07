@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
    <style>
        /* Panel admin limpio y responsive */
    .admin-wrapper { max-width: 1100px; margin: 30px auto; padding: 20px; }
    .admin-grid { display: flex; gap: 40px; align-items: center; justify-content: center; }

        /* Note: the global nav is provided by the layout; avoid duplicating it here */

        .admin-main { flex: 1; }
        .admin-title { font-size: 28px; color: #222; margin: 0 0 10px 0; }
        .admin-sub { color: #666; margin-bottom: 24px; }

        .admin-actions { display: flex; gap: 24px; flex-wrap: wrap; }
        .admin-btn {
            display: inline-block;
            padding: 14px 34px;
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: transform .08s ease, box-shadow .12s ease;
        }
        .admin-btn:hover { transform: translateY(-3px); }
        .admin-btn--primary { background: linear-gradient(#2DA1F7,#1B82D8); }
        .admin-btn--accent { background: linear-gradient(#FFB04A,#FF9800); }

        @media (max-width: 780px) {
            .admin-grid { flex-direction: column; align-items: stretch; }
            .admin-nav { width: 100%; }
            .admin-actions { justify-content: center; }
        }
    </style>

    <div class="admin-wrapper">
        <div class="admin-grid">
            <section class="admin-main">
                <h1 class="admin-title">Panel Central de Administración</h1>
                <p class="admin-sub">Usa este panel para llevar el control de las órdenes y actualizar el menú del café.</p>

                <div class="admin-actions">
                    <a class="admin-btn admin-btn--primary" href="{{ route('admin.gestion') }}">Ver Pedidos Recibidos</a>
                    <a class="admin-btn admin-btn--accent" href="{{ route('productos.index') }}">Gestionar Menú</a>
                </div>
            </section>
        </div>
    </div>

@endsection