# Limpiar Cache en Producción (Render)

## Paso 1: Configurar Token en Render

1. Ve a tu Dashboard de Render
2. Selecciona tu servicio web
3. Ve a **Environment**
4. Agrega una nueva variable:
   - **Key:** `ADMIN_CLEAR_TOKEN`
   - **Value:** `mi-token-secreto-123` (usa cualquier texto seguro)
5. Click en **Save Changes**
6. Espera que el servicio se reinicie (1-2 minutos)

## Paso 2: Limpiar el Cache

Abre tu navegador y ve a:

```
https://tu-app.onrender.com/ops/clear-cache?token=mi-token-secreto-123
```

Reemplaza:
- `tu-app.onrender.com` con tu URL real de Render
- `mi-token-secreto-123` con el token que configuraste

Deberías ver:
```json
{
  "view": "...",
  "status": "ok"
}
```

## Paso 3: Probar de Nuevo

1. Ve al kiosco
2. Agrega productos
3. Confirma pedido con nombre de cliente
4. **Ahora debería redirigir a la página de confirmación** ✅

---

## Alternativa: Forzar Rebuild

Si lo anterior no funciona:

1. En Render Dashboard
2. **Manual Deploy** → **Clear build cache & deploy**
3. Espera 5-10 minutos
