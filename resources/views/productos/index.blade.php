<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
</head>
<body>
    <div class="productos-container">
        <div class="productos-header">
            <h1 class="productos-title">📝 Productos</h1>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('productos.create') }}" class="btn btn-green">➕ Nuevo Producto</a>
                <a href="{{ route('admin.panel') }}" class="btn btn-green">← Volver al Panel</a>
            </div>
        </div>

        @if($productos->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <div class="empty-state-text">No hay productos registrados</div>
            </div>
        @else
            <div class="productos-grid">
                @foreach ($productos as $producto)
                    <div class="producto-card">
                        @if(!empty($producto->imagen_nombre))
                            <img src="{{ asset('img/productos/' . $producto->imagen_nombre) }}" alt="{{ $producto->nombre }}" class="producto-imagen">
                        @else
                            <div class="producto-imagen" style="display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                                Sin imagen
                            </div>
                        @endif

                        <div class="producto-body">
                            <div class="producto-nombre">{{ $producto->nombre }}</div>
                            <div class="producto-categoria">{{ optional($producto->categoria)->nombre ?? 'Sin categoría' }}</div>
                            <div class="producto-precio">S/. {{ number_format($producto->precio, 2) }}</div>

                            <div class="producto-actions">
                                <a href="/productos/{{ $producto->id }}/edit" class="btn-edit">✏️ Editar</a>
                                
                                <form action="/productos/{{ $producto->id }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Eliminar este producto?')" class="btn-delete">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
