# 🖨️ Configuración Impresora Xprinter XP-Q260NL

## 📋 Especificaciones

- **Modelo:** Xprinter XP-Q260NL
- **Ancho:** 80mm
- **Comandos:** ESC/POS ✅
- **Interface:** USB/LAN/RS232
- **Velocidad:** 200mm/s

---

## ⚙️ Configuración en Windows

### 1. Instalar Driver

```
1. Conectar impresora por USB
2. Descargar driver desde: https://www.xprintertech.com
3. Instalar driver Xprinter XP-Q260NL
4. Reiniciar PC
```

### 2. Configurar como Predeterminada

```
1. Panel de Control > Dispositivos e impresoras
2. Clic derecho en "Xprinter XP-Q260NL"
3. "Establecer como impresora predeterminada"
```

### 3. Configurar Tamaño de Papel

```
1. Clic derecho en impresora > Preferencias de impresión
2. Tamaño de papel: 80mm (Custom)
3. Ancho: 80mm
4. Alto: Continuo (o 297mm)
5. Márgenes: 0mm (todos)
6. Aplicar > Aceptar
```

---

## 🌐 Configuración en Navegador

### Google Chrome

```
1. Abrir ticket (cocina o caja)
2. Ctrl+P (Imprimir)
3. Destino: Xprinter XP-Q260NL
4. Más opciones:
   - Márgenes: Ninguno
   - Escala: 100%
   - Páginas por hoja: 1
5. ✅ Guardar configuración
```

### Microsoft Edge

```
1. Abrir ticket
2. Ctrl+P
3. Impresora: Xprinter XP-Q260NL
4. Diseño: Vertical
5. Márgenes: Ninguno
6. Escala: 100%
```

---

## 🧪 Prueba de Impresión

### Paso 1: Imprimir Página de Prueba

```
1. Panel de Control > Impresoras
2. Clic derecho en Xprinter
3. "Imprimir página de prueba"
4. Verificar que imprime correctamente
```

### Paso 2: Probar Ticket del Sistema

```
1. Ir a: http://localhost:8000/tickets/1/cocina
2. Clic en "🖨️ Imprimir"
3. Verificar formato
```

---

## ✅ Checklist de Configuración

- [ ] Driver instalado
- [ ] Impresora conectada (USB/LAN)
- [ ] Impresora encendida
- [ ] Papel térmico 80mm cargado
- [ ] Impresora como predeterminada
- [ ] Tamaño papel: 80mm
- [ ] Márgenes: 0mm
- [ ] Página de prueba impresa OK
- [ ] Ticket de prueba impreso OK

---

## 🐛 Solución de Problemas

### No imprime nada

```
✓ Verificar cable USB conectado
✓ Verificar impresora encendida
✓ Verificar papel cargado
✓ Reinstalar driver
```

### Imprime cortado

```
✓ Configurar márgenes en 0mm
✓ Verificar ancho de papel: 80mm
✓ Escala al 100%
```

### Imprime muy pequeño

```
✓ Cambiar escala a 100%
✓ Verificar configuración de fuente
✓ Limpiar caché del navegador
```

### Imprime en blanco

```
✓ Verificar papel térmico (lado correcto)
✓ Limpiar cabezal de impresión
✓ Verificar temperatura de impresión
```

---

## 🔌 Conexión por Red (LAN)

Si usas conexión LAN en lugar de USB:

```
1. Conectar cable Ethernet a impresora
2. Imprimir página de configuración (botón en impresora)
3. Anotar IP asignada (ej: 192.168.1.100)
4. Agregar impresora de red en Windows:
   - Panel de Control > Agregar impresora
   - "La impresora no está en la lista"
   - "Agregar por dirección TCP/IP"
   - IP: 192.168.1.100
   - Puerto: 9100
5. Instalar driver Xprinter
```

---

## 📱 Impresión desde Móvil (Opcional)

Para imprimir desde tablet/móvil:

```
1. Conectar impresora por LAN
2. Configurar IP estática
3. Acceder al sistema desde móvil
4. Los tickets se abrirán y podrán imprimirse
```

---

## 🎯 Configuración Óptima

```
Ancho de papel:     80mm
Márgenes:           0mm (todos)
Orientación:        Vertical
Escala:             100%
Calidad:            Alta
Velocidad:          Normal (200mm/s)
Densidad:           Media-Alta
Corte automático:   Activado (si disponible)
```

---

## 📞 Soporte Técnico

**Xprinter:**
- Web: https://www.xprintertech.com
- Email: support@xprinter.net
- Manual: Incluido con impresora

**Sistema Kiosco:**
- Ver: TICKETS_FEATURE.md
- Troubleshooting: README.md

---

## ✨ Tips

1. **Papel térmico:** Usar papel de calidad para mejor impresión
2. **Limpieza:** Limpiar cabezal cada 3 meses
3. **Backup:** Tener rollo de papel de repuesto
4. **Pruebas:** Hacer prueba de impresión diaria
5. **Configuración:** Guardar configuración del navegador

---

**¡Listo para imprimir! 🎉**
