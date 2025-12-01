# 📱 Modo Kiosco - Sin Barra de Navegación

## ✅ Solución Implementada: PWA (Progressive Web App)

Tu web ahora se puede instalar como una "app" en la tablet. Al abrirla, NO mostrará la barra de URL.

---

## 🚀 Cómo Instalar en la Tablet (Android/iPad)

### 📱 Android (Chrome):

1. Abre: `https://proyecto-empresa-web-eepa.onrender.com`
2. Toca el menú (⋮) arriba a la derecha
3. Selecciona **"Agregar a pantalla de inicio"** o **"Instalar app"**
4. Confirma

✅ Se creará un ícono en la pantalla de inicio
✅ Al abrirlo, se verá como app (SIN barra de URL)

---

### 🍎 iPad/iPhone (Safari):

1. Abre: `https://proyecto-empresa-web-eepa.onrender.com`
2. Toca el botón de compartir (□↑)
3. Selecciona **"Agregar a pantalla de inicio"**
4. Confirma

✅ Se creará un ícono en la pantalla de inicio
✅ Al abrirlo, se verá como app (SIN barra de URL)

---

## 🎯 Resultado:

### ANTES (Navegador normal):
```
┌─────────────────────────────┐
│ ← → ⟳ proyecto-empresa...  │ ← Barra de URL
├─────────────────────────────┤
│                             │
│   REBEL JUNGLE              │
│   Contenido...              │
│                             │
└─────────────────────────────┘
```

### DESPUÉS (App instalada):
```
┌─────────────────────────────┐
│   REBEL JUNGLE              │ ← Sin barra
│   Contenido...              │
│                             │
│                             │
│                             │
└─────────────────────────────┘
```

---

## 🔧 Configuración Adicional (Opcional)

### Para bloquear la tablet en modo kiosco:

#### Android:
1. Ajustes → Seguridad → **"Fijar pantalla"**
2. Abre la app
3. Botón de apps recientes → Toca el ícono de la app → **"Fijar"**

#### iPad:
1. Ajustes → Accesibilidad → **"Acceso guiado"**
2. Actívalo
3. Abre la app
4. Triple clic en botón lateral → Iniciar acceso guiado

---

## 📋 Ventajas de PWA:

✅ Sin barra de navegación
✅ Pantalla completa
✅ Ícono en pantalla de inicio
✅ Funciona offline (caché)
✅ Parece app nativa
✅ No necesita Google Play / App Store

---

## 🎨 Personalización (Opcional)

Si quieres cambiar el ícono de la app, crea estas imágenes:

- `public/img/icon-192.png` (192x192 px)
- `public/img/icon-512.png` (512x512 px)

Usa el logo de Rebel Jungle.

---

## ✅ Verificar que funciona:

1. Abre Chrome en la tablet
2. Ve a: `https://proyecto-empresa-web-eepa.onrender.com`
3. Deberías ver un mensaje: **"Instalar app"** o un ícono de descarga
4. Si NO aparece, espera 5 segundos y recarga la página

---

**¡Modo kiosco listo!** 🎉
