# 🚀 Guía de Despliegue Rápido

## ✅ Método Recomendado: Deploy Hook

Para ahorrar minutos en Render, usa el **Deploy Hook** en lugar de hacer push a Git cada vez.

### 🎯 ¿Cuándo usar cada método?

| Método | Cuándo usarlo | Minutos Render |
|--------|---------------|----------------|
| **Deploy Hook** | Cambios pequeños, pruebas rápidas | ⚡ Mínimos |
| **Git Push** | Cambios importantes, nuevas features | 🔄 Normales |

---

## 🔥 Opción 1: Script Automático (Más Fácil)

### Windows:
```bash
# Doble click en:
deploy.bat
```

### Linux/Mac:
```bash
chmod +x deploy.sh
./deploy.sh
```

---

## 🛠️ Opción 2: Comando Manual

```bash
curl -X POST "https://api.render.com/deploy/srv-d4667i3ipnbc73bifuh0?key=itJ-lE-qB_k"
```

---

## 📋 Flujo de Trabajo Completo

### Para cambios en el código:

```bash
# 1. Hacer cambios en tu código
# 2. Commitear localmente (opcional)
git add .
git commit -m "Descripción del cambio"

# 3. Push a GitHub
git push

# 4. Deploy automático en Render
deploy.bat  # o curl manual
```

### Para deploy rápido sin commit:

```bash
# Solo ejecuta:
deploy.bat
```

---

## ⚠️ Importante

- **La URL del Deploy Hook es SECRETA** - No la compartas
- **Cada llamada inicia un nuevo build** - Úsala con cuidado
- **Render construye desde la última versión en GitHub** - Asegúrate de hacer push primero si hay cambios

---

## 🔍 Verificar el Deploy

1. Ejecuta `deploy.bat`
2. Ve a: https://dashboard.render.com
3. Verifica que el build esté corriendo
4. Espera a que termine (2-3 minutos)
5. Prueba tu app: https://proyecto-empresa-web-eepa.onrender.com

---

## 💡 Tips

- ✅ Usa el Deploy Hook para deploys rápidos
- ✅ Haz push a Git para mantener historial
- ✅ Prueba localmente antes de deployar
- ✅ Revisa los logs en Render Dashboard

---

**¡Deploy listo en segundos!** ⚡
