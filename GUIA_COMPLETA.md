# 📖 Guía Completa - Sistema de Impresión Automática

## 🎯 **Objetivo:**

Que la dueña pueda usar **solo la tablet** para marcar pedidos como pagados, y la **PC imprime automáticamente**.

---

## 🏠 **PARTE 1: En tu Casa (AHORA)**

### **Paso 1: Subir código a GitHub** (2 min)

```bash
git add .
git commit -m "Sistema de impresión automática desde tablet"
git push
```

### **Paso 2: Esperar despliegue en Render** (3 min)

Render detectará los cambios y desplegará automáticamente.

Verificar en: https://proyecto-empresa-web-eepa.onrender.com

### **Paso 3: Ejecutar migración** (1 min)

**Opción A:** Desde Render Dashboard → Shell:
```bash
php artisan migrate
```

**Opción B:** Desde navegador (si configuraste token):
```
https://proyecto-empresa-web-eepa.onrender.com/ops/clear-cache?token=TU_TOKEN
```

### **Paso 4: Preparar archivos para la tienda** (1 min)

Ejecutar:
```cmd
PREPARAR_PARA_TIENDA.bat
```

Esto creará la carpeta `Kiosco-Instalador` con todo lo necesario.

### **Paso 5: Copiar a USB** (1 min)

Copiar la carpeta `Kiosco-Instalador` a una USB.

**Total en tu casa: 8 minutos**

---

## 🏪 **PARTE 2: En la Tienda (DESPUÉS)**

### **Paso 1: Instalar Python** (5 min)

1. Ir a: https://www.python.org/downloads/
2. Descargar e instalar
3. ✅ **IMPORTANTE:** Marcar "Add Python to PATH"

### **Paso 2: Copiar archivos** (1 min)

Copiar carpeta `Kiosco-Instalador` de la USB a la PC.

### **Paso 3: Ejecutar instalador** (2 min)

Doble click en: `INSTALAR_EN_TIENDA.bat`

El instalador hará TODO automáticamente:
- ✅ Verificar Python
- ✅ Crear carpeta C:\Kiosco
- ✅ Copiar archivos
- ✅ Instalar dependencias
- ✅ Configurar inicio automático

### **Paso 4: Probar** (2 min)

1. El instalador pregunta si quieres iniciar → Responde: **S**
2. Se abre el programa
3. Desde tablet: Marcar pedido como pagado
4. ✅ PC imprime automáticamente

**Total en la tienda: 10 minutos**

---

## 🔄 **Cómo Funciona:**

```
┌─────────────────────────────────────┐
│  TABLET                             │
│  - Abre /caja en Render             │
│  - Click "💵 Efectivo"              │
│  - Pedido marcado como pagado       │
└──────────────┬──────────────────────┘
               │
               ↓ Internet
               │
┌──────────────┴──────────────────────┐
│  RENDER (Nube)                      │
│  - Guarda: pagado=true              │
│  - Guarda: impreso=false            │
└──────────────┬──────────────────────┘
               │
               ↓ Consulta cada 3 seg
               │
┌──────────────┴──────────────────────┐
│  PC (Programa Python)               │
│  - Consulta pedidos pendientes      │
│  - Descarga datos                   │
│  - Formatea ticket                  │
│  - Envía a impresora                │
│  - Marca como impreso               │
└──────────────┬──────────────────────┘
               │
               ↓ USB
               │
┌──────────────┴──────────────────────┐
│  IMPRESORA XPRINTER                 │
│  ✅ Imprime ticket                   │
└─────────────────────────────────────┘
```

---

## 📁 **Archivos Importantes:**

### **Para llevar a la tienda:**
```
Kiosco-Instalador/
├── INSTALAR_EN_TIENDA.bat ← PRINCIPAL
├── auto_print.py
├── requirements.txt
├── iniciar_impresora.bat
├── INSTALACION_IMPRESORA.md
└── INSTRUCCIONES_TIENDA.md
```

### **Documentación:**
- `INSTRUCCIONES_TIENDA.md` - Guía simple para la tienda
- `INSTALACION_IMPRESORA.md` - Guía técnica completa
- `RESUMEN_CAMBIOS.md` - Qué se modificó en el código

---

## ✅ **Verificación:**

### **En tu casa:**
```
☐ Código subido a GitHub
☐ Render desplegó correctamente
☐ Migración ejecutada
☐ API funciona: /api/print/pending
☐ Carpeta Kiosco-Instalador creada
☐ Archivos copiados a USB
```

### **En la tienda:**
```
☐ Python instalado
☐ Instalador ejecutado
☐ Programa iniciado
☐ Prueba exitosa
☐ Inicio automático configurado
```

---

## 🎯 **Ventajas:**

✅ **Tablet solo para pedidos** - Más fácil
✅ **PC maneja impresión** - Más confiable
✅ **Automático** - Sin intervención
✅ **Sin Bluetooth** - Usa USB existente
✅ **Sin costo** - No requiere hardware nuevo
✅ **Inicio automático** - Se inicia con la PC
✅ **Instalación fácil** - Un solo click

---

## 🐛 **Solución de Problemas:**

### **"Python no se reconoce"**
→ Reinstalar Python y marcar "Add to PATH"

### **"No se encontró impresora"**
→ Verificar que esté encendida y conectada

### **"Error de conexión"**
→ Verificar internet y que Render funcione

### **"No imprime"**
→ Verificar que el pedido esté pagado y no impreso

---

## 📞 **Soporte:**

Si tienes problemas:
1. Revisa `INSTALACION_IMPRESORA.md`
2. Verifica logs del programa
3. Verifica que Render funcione
4. Contacta al desarrollador

---

## 🎉 **¡Sistema Completo!**

Ahora tienes:
- ✅ Kiosco funcional
- ✅ Gestión de pedidos
- ✅ Caja con impresión
- ✅ **Impresión automática desde tablet**

**La dueña puede usar solo la tablet para todo.**

---

**Tiempo total:**
- En tu casa: 8 minutos
- En la tienda: 10 minutos
- **Total: 18 minutos**
