# 🖨️ Sistema Local de Impresión Automática

## 🎯 **Qué hace:**

```
Tablet → Marca como pagado en Render
    ↓
PC → Consulta cada 3 segundos
    ↓
PC → Imprime automáticamente
```

---

## ✅ **Ventajas:**

- ✅ No requiere instalar nada en la tablet
- ✅ Solo PHP (ya lo tienes instalado)
- ✅ Imprime automáticamente
- ✅ Funciona en segundo plano
- ✅ Detecta pedidos nuevos en tiempo real

---

## 📋 **Requisitos:**

1. PHP instalado en la PC (ya lo tienes para Laravel)
2. Impresora conectada
3. Internet

---

## 🚀 **Instalación (2 minutos):**

### **Paso 1: Copiar archivos**

Copiar estos archivos a `C:\Kiosco`:
- `print_local.php`
- `iniciar_impresion.bat`

### **Paso 2: Ejecutar**

Doble click en: `iniciar_impresion.bat`

### **Paso 3: Probar**

1. Desde tablet, marca un pedido como pagado
2. Espera 3 segundos
3. ✅ La PC imprimirá automáticamente

---

## ⚙️ **Configuración:**

### **Cambiar impresora:**

Editar `print_local.php`, línea 9:
```php
$PRINTER_NAME = "XPrinter XP-58"; // Nombre de tu impresora
```

### **Cambiar intervalo:**

Editar `print_local.php`, línea 8:
```php
$CHECK_INTERVAL = 5; // Cambiar a 5 segundos
```

---

## 🔄 **Inicio Automático:**

### **Para que se inicie con Windows:**

1. Presionar: `Windows + R`
2. Escribir: `shell:startup`
3. Enter
4. Copiar `iniciar_impresion.bat` ahí
5. ✅ Se iniciará automáticamente

---

## 🐛 **Solución de Problemas:**

### **"PHP no se reconoce"**
→ Instalar PHP o agregar a PATH

### **"Error de conexión"**
→ Verificar internet y que Render funcione

### **"No imprime"**
→ Verificar que la impresora esté encendida

---

## 💡 **Uso Diario:**

1. Encender PC
2. Doble click en `iniciar_impresion.bat`
3. Dejar corriendo
4. ✅ Imprime automáticamente

---

## ✅ **Ventajas vs Python:**

| Característica | Python | PHP |
|----------------|--------|-----|
| Instalación | Compleja | Simple |
| Dependencias | Muchas | Ninguna |
| Tamaño | ~100MB | ~0MB |
| Velocidad | Rápida | Rápida |
| Compatibilidad | Buena | Excelente |

---

## 🎉 **¡Listo!**

Ahora la tablet puede marcar pedidos como pagados y la PC imprimirá automáticamente sin intervención.
