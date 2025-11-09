# 🔧 Ejecutar Migración en Render

## Problema
Los botones de pago (Yape/Efectivo) y eliminar no aparecen en /caja porque falta ejecutar la migración que agrega los campos necesarios a la base de datos.

## Solución

### Opción 1: Usar el Shell de Render (Recomendado)

1. Ve a tu Dashboard de Render: https://dashboard.render.com
2. Selecciona tu servicio: **proyecto-empresa-web-eepa**
3. En el menú lateral, haz clic en **"Shell"**
4. Ejecuta el siguiente comando:

```bash
php artisan migrate --force
```

5. Deberías ver algo como:
```
Running migrations...
2025_01_29_000001_add_payment_and_deletion_fields_to_pedidos .... DONE
```

### Opción 2: Usar la ruta /ops/clear-cache

1. Primero, agrega esta variable de entorno en Render:
   - Ve a **Environment** en tu servicio
   - Agrega: `ADMIN_CLEAR_TOKEN` = `tu_token_secreto_123`
   - Guarda los cambios

2. Luego visita (reemplaza con tu token):
```
https://proyecto-empresa-web-eepa.onrender.com/ops/clear-cache?token=tu_token_secreto_123
```

3. Después, en el Shell de Render ejecuta:
```bash
php artisan migrate --force
```

## Verificación

Después de ejecutar la migración, visita:
https://proyecto-empresa-web-eepa.onrender.com/caja

Deberías ver los botones:
- 💵 Pagar en Efectivo
- 📱 Pagar con Yape
- 🗑️ Eliminar

## Cambios Realizados

✅ Botones de Caja/Cocina/Ventas movidos de la página de inicio al menú de productos
✅ Ahora aparecen después de seleccionar "Para Aquí" o "Para Llevar"
