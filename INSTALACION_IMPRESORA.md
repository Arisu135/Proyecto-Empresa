# 🖨️ Instalación del Sistema de Impresión Automática

## 📋 **Qué hace este sistema:**

```
Tablet → Marca pedido como pagado en Render
    ↓
PC (con programa) → Consulta cada 3 segundos
    ↓
Si hay pedido nuevo → Imprime automáticamente
    ↓
Impresora USB → Imprime ticket
```

---

## 🏪 **INSTRUCCIONES PARA LA TIENDA**

### **Paso 1: Instalar Python** (5 minutos)

1. Descargar Python desde: https://www.python.org/downloads/
2. Ejecutar instalador
3. ✅ **IMPORTANTE:** Marcar "Add Python to PATH"
4. Click "Install Now"
5. Esperar a que termine

**Verificar instalación:**
```cmd
python --version
```
Debe mostrar: `Python 3.x.x`

---

### **Paso 2: Descargar archivos** (2 minutos)

**Opción A: Desde GitHub**
1. Ir a: https://github.com/tu-usuario/Proyecto-Empresa
2. Click en "Code" → "Download ZIP"
3. Descomprimir en: `C:\Kiosco`

**Opción B: Copiar archivos**
1. Copiar estos archivos a `C:\Kiosco`:
   - `auto_print.py`
   - `requirements.txt`

---

### **Paso 3: Instalar dependencias** (3 minutos)

Abrir CMD como Administrador:

```cmd
cd C:\Kiosco
python -m pip install -r requirements.txt
```

Esperar a que termine la instalación.

---

### **Paso 4: Probar el programa** (2 minutos)

```cmd
cd C:\Kiosco
python auto_print.py
```

**Deberías ver:**
```
==================================================
🖨️  SERVIDOR DE IMPRESIÓN AUTOMÁTICA
==================================================
📡 Conectando a: https://proyecto-empresa-web-eepa.onrender.com
🖨️  Impresora: XPrinter XP-58
⏱️  Intervalo de consulta: 3 segundos
==================================================
✅ Sistema iniciado. Presiona Ctrl+C para detener.
```

---

### **Paso 5: Probar impresión** (3 minutos)

1. Desde la tablet, ir a: `/caja`
2. Marcar un pedido como pagado
3. **En la PC:** El programa detectará el pedido
4. **Imprimirá automáticamente**
5. ✅ Verificar que salió el ticket

---

### **Paso 6: Configurar inicio automático** (5 minutos)

**Para que el programa se inicie solo al encender la PC:**

1. Crear archivo `iniciar_impresora.bat`:

```batch
@echo off
cd C:\Kiosco
python auto_print.py
pause
```

2. Guardar en: `C:\Kiosco\iniciar_impresora.bat`

3. Crear acceso directo:
   - Click derecho en `iniciar_impresora.bat`
   - "Crear acceso directo"
   - Copiar el acceso directo

4. Pegar en carpeta de inicio:
   - Presionar: `Windows + R`
   - Escribir: `shell:startup`
   - Enter
   - Pegar el acceso directo ahí

5. ✅ Listo - Se iniciará automáticamente

---

## 🔧 **Configuración Opcional**

### **Cambiar impresora específica:**

Editar `auto_print.py`, línea 13:
```python
PRINTER_NAME = "XPrinter XP-58"  # Nombre exacto de tu impresora
```

### **Cambiar intervalo de consulta:**

Editar `auto_print.py`, línea 12:
```python
CHECK_INTERVAL = 5  # Cambiar a 5 segundos
```

---

## ✅ **Verificación Final**

### **Checklist:**
```
☐ Python instalado
☐ Dependencias instaladas
☐ Programa ejecutándose
☐ Impresora conectada y funcionando
☐ Prueba de impresión exitosa
☐ Inicio automático configurado
```

---

## 🎯 **Uso Diario**

### **Opción 1: Inicio automático (Recomendado)**
- Encender PC
- El programa se inicia solo
- ✅ Listo para usar

### **Opción 2: Inicio manual**
- Doble click en `iniciar_impresora.bat`
- ✅ Listo para usar

---

## 🐛 **Solución de Problemas**

### **Problema: "Python no se reconoce"**
**Solución:** Reinstalar Python y marcar "Add to PATH"

### **Problema: "No se encontró impresora"**
**Solución:** 
1. Verificar que la impresora esté encendida
2. Verificar que Windows la reconozca
3. Establecerla como predeterminada

### **Problema: "Error de conexión"**
**Solución:**
1. Verificar internet
2. Verificar que Render esté funcionando
3. Abrir: https://proyecto-empresa-web-eepa.onrender.com

### **Problema: "No imprime"**
**Solución:**
1. Verificar que el pedido esté marcado como pagado
2. Verificar que no esté marcado como impreso
3. Reiniciar el programa

---

## 📞 **Soporte**

Si tienes problemas, contacta al desarrollador con:
- Captura de pantalla del error
- Versión de Python
- Nombre de la impresora

---

## 🎉 **¡Listo!**

Ahora la tablet puede marcar pedidos como pagados y la PC imprimirá automáticamente.

**Flujo completo:**
```
1. Cliente hace pedido en tablet
2. Cocina prepara
3. Caja marca como pagado en tablet
4. PC imprime automáticamente
5. ✅ Cliente recibe ticket
```
