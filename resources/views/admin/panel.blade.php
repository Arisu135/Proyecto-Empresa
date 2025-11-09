<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        html, body {
            background: #ffffff !important;
        }
        .admin-wrapper {
            background: #ffffff !important;
        }
        .admin-title h1 {
            color: #1f2937 !important;
        }
        .admin-title p {
            color: #6b7280 !important;
        }
        .admin-btn {
            background: #1f2937 !important;
            color: white !important;
        }
        .admin-btn:hover {
            background: #111827 !important;
        }
    </style>
</head>
<body style="background: #ffffff !important;">
    <div class="admin-wrapper">
        <div class="admin-header">
            <a href="{{ route('catalogo.index') }}" class="admin-btn">← Volver al Inicio</a>
            <a href="{{ route('admin.logout') }}" class="admin-btn logout">🚪 Cerrar Sesión</a>
        </div>

        <div class="admin-title">
            <h1>Panel de Administración</h1>
            <p>Rebel Jungle</p>
        </div>

        <div class="admin-grid">
            <a href="{{ route('admin.ventas') }}" class="admin-card">
                <div class="admin-card-icon">📊</div>
                <div class="admin-card-title">Historial Ventas</div>
                <div class="admin-card-desc">Ver todas las ventas</div>
            </a>

            <a href="{{ route('admin.ventas.eliminadas') }}" class="admin-card">
                <div class="admin-card-icon">🗑️</div>
                <div class="admin-card-title">Ventas Eliminadas</div>
                <div class="admin-card-desc">Historial completo</div>
            </a>

            <a href="{{ route('admin.ventas.eliminadas.cocina') }}" class="admin-card">
                <div class="admin-card-icon">🍳</div>
                <div class="admin-card-title">Eliminadas Cocina</div>
                <div class="admin-card-desc">Solo desde cocina</div>
            </a>

            <a href="{{ route('productos.index') }}" class="admin-card">
                <div class="admin-card-icon">📝</div>
                <div class="admin-card-title">Productos</div>
                <div class="admin-card-desc">Editar menú y precios</div>
            </a>

            <a href="{{ route('productos.create') }}" class="admin-card">
                <div class="admin-card-icon">➕</div>
                <div class="admin-card-title">Nuevo Producto</div>
                <div class="admin-card-desc">Añadir al menú</div>
            </a>
        </div>
    </div>
</body>
</html>
