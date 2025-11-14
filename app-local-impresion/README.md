# 🖨️ Sistema de Impresión Térmica Mejorado

## ✅ Ventajas de este sistema:

- ✅ Usa librería especializada para impresoras térmicas
- ✅ Mejor calidad de impresión
- ✅ Comandos ESC/POS nativos
- ✅ Compatible con Xprinter, Epson, Star
- ✅ Texto en GRANDE y NEGRITA

---

## 📋 Requisitos:

1. Node.js instalado
2. Impresora térmica conectada por USB
3. Internet

---

## 🚀 Instalación (5 minutos):

### Paso 1: Instalar Node.js
1. Descargar: https://nodejs.org/
2. Instalar (Next, Next, Finish)
3. Reiniciar PC

### Paso 2: Copiar archivos
Copiar carpeta `app-local-impresion` a `C:\Kiosco`

### Paso 3: Ejecutar
Doble click en: `iniciar.bat`

---

## 🎯 Uso:

1. Ejecutar `iniciar.bat` (una vez al día)
2. Dejar corriendo
3. Desde tablet/celular marcar como pagado
4. ✅ Imprime automáticamente

---

## ⚙️ Configuración:

### Cambiar tipo de impresora:

Editar `index.js`, línea 11:
```javascript
type: 'epson',  // Opciones: epson, star, tanca
```

### Cambiar intervalo:

Editar `index.js`, línea 6:
```javascript
const CHECK_INTERVAL = 5000; // 5 segundos
```

---

## 🐛 Solución de Problemas:

### "Node no se reconoce"
→ Instalar Node.js y reiniciar PC

### "No encuentra impresora"
→ Verificar que esté conectada por USB

### "Error de conexión"
→ Verificar internet

---

## ✅ Ventajas vs PHP:

| Característica | PHP | Node.js |
|----------------|-----|---------|
| Calidad impresión | Básica | ⭐ Excelente |
| Comandos ESC/POS | Manual | ⭐ Automático |
| Texto grande | Limitado | ⭐ Perfecto |
| Compatibilidad | Media | ⭐ Alta |
| Instalación | Fácil | Media |

---

## 🎉 ¡Listo!

Ahora tendrás impresiones de calidad profesional.
