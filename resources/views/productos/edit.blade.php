<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="{{ asset('css/producto-edit.css') }}">
</head>
<body>
    <div class="edit-container">
        <h1 class="edit-title">Editar Producto: {{ $producto->nombre }}</h1>

        <form method="POST" action="/productos/{{ $producto->id }}" enctype="multipart/form-data">
            @csrf 
            @method('PUT')

            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="{{ $producto->nombre }}" required class="form-input">
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required class="form-textarea">{{ $producto->descripcion }}</textarea>
            </div>

            <div class="form-group">
                <label for="precio" class="form-label">Precio (S/.):</label>
                <input type="number" id="precio" name="precio" value="{{ $producto->precio }}" step="0.01" required class="form-input">
            </div>

            <div class="form-group">
                <label for="categoria" class="form-label">Categoría:</label>
                <select id="categoria" name="categoria" required class="form-select">
                    <option value="Combos" {{ $producto->categoria == 'Combos' ? 'selected' : '' }}>Combos</option>
                    <option value="Individual" {{ $producto->categoria == 'Individual' ? 'selected' : '' }}>Individual</option>
                    <option value="Bebidas" {{ $producto->categoria == 'Bebidas' ? 'selected' : '' }}>Bebidas</option>
                    <option value="Postres" {{ $producto->categoria == 'Postres' ? 'selected' : '' }}>Postres</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Imagen Actual:</label>
                @if($producto->imagen_nombre)
                    <img src="{{ asset('img/productos/' . $producto->imagen_nombre) }}" alt="{{ $producto->nombre }}" style="max-width: 200px; border-radius: 8px; margin-bottom: 10px;">
                @else
                    <p style="color: #666;">Sin imagen</p>
                @endif
            </div>

            <div class="form-group">
                <label for="imagen" class="form-label">Cambiar Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" class="form-input">
                <small style="color: #666;">Deja vacío si no quieres cambiar la imagen</small>
            </div>

            <button type="submit" class="btn-submit">Actualizar Producto</button>
        </form>
        
        <a href="/productos" class="btn-back">← Volver al Menú</a>
    </div>
</body>
</html>
