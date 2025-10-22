<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto: {{ $producto->nombre }}</h1>

    <form method="POST" action="/productos/{{ $producto->id }}">
        @csrf 
        @method('PUT') 

        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" value="{{ $producto->nombre }}" required><br><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion" required>{{ $producto->descripcion }}</textarea><br><br>

        <label for="precio">Precio (S/.):</label><br>
        <input type="number" id="precio" name="precio" value="{{ $producto->precio }}" step="0.01" required><br><br>

        <label for="categoria">Categoría:</label><br>
        <select id="categoria" name="categoria" required>
            <option value="Combos" {{ $producto->categoria == 'Combos' ? 'selected' : '' }}>Combos</option>
            <option value="Individual" {{ $producto->categoria == 'Individual' ? 'selected' : '' }}>Individual</option>
            <option value="Bebidas" {{ $producto->categoria == 'Bebidas' ? 'selected' : '' }}>Bebidas</option>
            <option value="Postres" {{ $producto->categoria == 'Postres' ? 'selected' : '' }}>Postres</option>
        </select><br><br>

        <button type="submit">Actualizar Producto</button>
    </form>
    
    <hr>
    <a href="/productos">Volver al Menú</a>
</body>
</html>