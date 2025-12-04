# 🎫 Sistema de Tickets y Agregar Productos

## ✅ Funcionalidades Implementadas

### 1. 📦 Agregar Productos a Pedido Existente (Caja)

**Flujo:**
1. En **Caja** (`/caja`), cada pedido tiene botón "➕ Agregar Productos"
2. Al hacer clic, se guarda el ID del pedido en sesión
3. Redirige al menú de productos (`/menu`)
4. Usuario selecciona productos normalmente
5. Al confirmar, los productos se agregan al pedido existente
6. El total del pedido se actualiza automáticamente

**Archivos modificados:**
- `app/Http/Controllers/CajaController.php` - Métodos `agregarProductos()` y `agregarProductosPost()`
- `app/Http/Controllers/CatalogoController.php` - Método `finalizarPedido()` detecta pedido existente
- `resources/views/caja/index.blade.php` - Botón "Agregar Productos"
- `routes/web.php` - Rutas `/caja/{pedido}/agregar-productos`

---

### 2. 🖨️ Sistema de Impresión de 2 Tickets

**Flujo:**
1. Usuario confirma pedido (pone nombre)
2. Sistema crea el pedido en BD
3. Redirige a **Cocina** (`/mesas`)
4. Se abren automáticamente 2 ventanas:
   - 🍳 **Ticket Cocina** - Con productos y opciones
   - 💰 **Ticket Caja** - Con precios y total

**Características de los Tickets:**

#### 🍳 Ticket Cocina (`/tickets/{pedido}/cocina`)
- Diseño optimizado para impresora térmica (80mm)
- Muestra:
  - Número de pedido
  - Cliente
  - Fecha y hora
  - Mesa (si aplica)
  - **Productos con cantidad grande y visible**
  - **Opciones personalizadas** (sin, extra, etc.)
- Botones: Imprimir y Cerrar
- Fuente: Courier New (monospace)

#### 💰 Ticket Caja (`/tickets/{pedido}/caja`)
- Diseño optimizado para impresora térmica (80mm)
- Muestra:
  - Número de pedido
  - Cliente
  - Fecha y hora
  - Mesa (si aplica)
  - **Productos con precios**
  - **Total a pagar**
  - Mensaje de agradecimiento
- Botones: Imprimir y Cerrar
- Fuente: Courier New (monospace)

**Archivos creados:**
- `resources/views/tickets/cocina.blade.php` - Vista ticket cocina
- `resources/views/tickets/caja.blade.php` - Vista ticket caja

**Archivos modificados:**
- `app/Http/Controllers/CatalogoController.php` - Métodos `ticketCocina()` y `ticketCaja()`
- `resources/views/mesas/index.blade.php` - Auto-apertura de tickets
- `routes/web.php` - Rutas `/tickets/{pedido}/cocina` y `/tickets/{pedido}/caja`

---

## 🚀 Cómo Usar

### Agregar Productos a Pedido Existente

```
1. Ir a Caja (/caja)
2. Buscar el pedido
3. Clic en "➕ Agregar Productos"
4. Seleccionar productos del menú
5. Confirmar pedido
6. Los productos se agregan al pedido original
```

### Imprimir Tickets

**Opción 1: Automático (al confirmar pedido)**
```
1. Confirmar pedido nuevo
2. Se abren 2 ventanas automáticamente
3. Imprimir cada ticket
```

**Opción 2: Manual (desde Cocina)**
```
1. Ir a Cocina (/mesas)
2. Si hay pedido confirmado, aparece banner azul
3. Clic en "🍳 Imprimir Ticket Cocina"
4. Clic en "💰 Imprimir Ticket Caja"
```

**Opción 3: Directo por URL**
```
/tickets/{id}/cocina  - Ticket para cocina
/tickets/{id}/caja    - Ticket para caja
```

---

## 🔧 Configuración de Impresora

### Impresora Térmica (Recomendado)
- Ancho: 80mm
- Configurar como impresora predeterminada
- En navegador: Configuración > Impresión > Sin márgenes

### Impresora Normal
- Los tickets se adaptan automáticamente
- Usar "Imprimir" desde el navegador
- Seleccionar tamaño de papel adecuado

---

## 📋 Rutas Agregadas

```php
// Agregar productos a pedido existente
GET  /caja/{pedido}/agregar-productos
POST /caja/{pedido}/agregar-productos

// Tickets de impresión
GET /tickets/{pedido}/cocina
GET /tickets/{pedido}/caja
```

---

## 🎨 Diseño de Tickets

### Características Comunes
- ✅ Ancho: 80mm (estándar térmico)
- ✅ Fuente: Courier New (monospace)
- ✅ Responsive para impresión
- ✅ Botones de acción (Imprimir/Cerrar)
- ✅ Bordes punteados para separación
- ✅ Información clara y legible

### Diferencias

| Característica | Ticket Cocina 🍳 | Ticket Caja 💰 |
|----------------|------------------|----------------|
| **Enfoque** | Preparación | Pago |
| **Cantidad** | Grande (20px) | Normal (14px) |
| **Opciones** | ✅ Visible | ❌ No muestra |
| **Precios** | ❌ No muestra | ✅ Visible |
| **Total** | ❌ No muestra | ✅ Grande |
| **Color** | Naranja | Verde |

---

## 🐛 Troubleshooting

### Los tickets no se abren automáticamente
- **Causa:** Bloqueador de pop-ups del navegador
- **Solución:** Permitir pop-ups para el sitio

### Los tickets no se imprimen bien
- **Causa:** Márgenes de impresora
- **Solución:** Configurar impresión sin márgenes

### No aparece el botón "Agregar Productos"
- **Causa:** Vista de caja no actualizada
- **Solución:** Limpiar caché del navegador (Ctrl+F5)

### Los productos no se agregan al pedido
- **Causa:** Sesión no guardada correctamente
- **Solución:** Verificar que la sesión esté activa

---

## 📊 Flujo Completo

```
┌─────────────────┐
│  Confirmar      │
│  Pedido         │
│  (Poner nombre) │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Crear Pedido   │
│  en BD          │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Redirigir a    │
│  Cocina         │
└────────┬────────┘
         │
         ├──────────────────┐
         │                  │
         ▼                  ▼
┌─────────────────┐  ┌─────────────────┐
│  🍳 Ticket      │  │  💰 Ticket      │
│  Cocina         │  │  Caja           │
│  (Productos)    │  │  (Precios)      │
└─────────────────┘  └─────────────────┘
```

---

## ✨ Mejoras Futuras (Opcional)

- [ ] Integración con impresora térmica USB directa
- [ ] API para sistema de impresión automática
- [ ] Historial de tickets impresos
- [ ] Reimprimir tickets desde historial
- [ ] Configuración de formato de ticket
- [ ] Soporte para múltiples impresoras

---

**Desarrollado para Corporación Orgánica Kiosco**

*Sistema de tickets optimizado para operación eficiente*
