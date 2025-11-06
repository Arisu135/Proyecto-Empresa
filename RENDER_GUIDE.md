# ============================================
# Guía de Despliegue en Render
# ============================================

## 📋 Pre-requisitos

Antes de desplegar, asegúrate de tener:

1. ✅ Cuenta en Render.com (gratis)
2. ✅ Repositorio Git (GitHub/GitLab/Bitbucket)
3. ✅ Código actualizado en el repositorio
4. ✅ Archivo `.env` NO incluido en Git

## 🚀 Pasos para Desplegar

### Paso 1: Preparar el Proyecto

```bash
# Ejecuta el script de preparación
chmod +x deploy-prepare.sh
./deploy-prepare.sh
```

Este script verificará:
- ✅ Archivos necesarios (Dockerfile, render.yaml, etc.)
- ✅ Validación de dependencias
- ✅ Estructura de directorios
- ✅ Seguridad (.env no rastreado)

### Paso 2: Prueba Local (Opcional pero Recomendado)

```bash
# Prueba localmente con Docker
chmod +x local-test.sh
./local-test.sh
```

Esto levantará el proyecto localmente en:
- App: http://localhost:8000
- Adminer: http://localhost:8080

### Paso 3: Subir Código a Git

```bash
# Si es la primera vez
git init
git add .
git commit -m "Initial commit: Proyecto listo para despliegue"
git branch -M main
git remote add origin <tu-repositorio>
git push -u origin main

# Si ya tienes Git configurado
git add .
git commit -m "Configuración lista para Render"
git push
```

### Paso 4: Crear Servicios en Render

#### Opción A: Usando Blueprint (Automático - RECOMENDADO)

1. Ve a https://dashboard.render.com
2. Haz clic en **"New +"** → **"Blueprint"**
3. Conecta tu repositorio
4. Render detectará automáticamente `render.yaml`
5. Haz clic en **"Apply"**
6. Espera 5-10 minutos para el primer despliegue

Render creará automáticamente:
- ✅ Servicio Web (Laravel)
- ✅ Base de datos PostgreSQL
- ✅ Variables de entorno
- ✅ Conexión entre servicios

#### Opción B: Manual (Paso a Paso)

##### 4.1. Crear Base de Datos PostgreSQL

1. En Render Dashboard: **"New +"** → **"PostgreSQL"**
2. Configuración:
   - **Name:** `proyecto-empresa-db`
   - **Database:** `proyecto_empresa`
   - **User:** (auto-generado)
   - **Region:** Oregon (o tu preferencia)
   - **Plan:** Free
3. Haz clic en **"Create Database"**
4. **IMPORTANTE:** Copia la **Internal Database URL** (la usarás después)

##### 4.2. Crear Web Service

1. **"New +"** → **"Web Service"**
2. Conecta tu repositorio
3. Configuración básica:
   - **Name:** `proyecto-empresa-web`
   - **Region:** Oregon (mismo que la BD)
   - **Branch:** main
   - **Runtime:** Docker
   - **Plan:** Free

4. **Variables de Entorno** (Environment Variables):

   ```
   APP_NAME=CorporacionOrganicaKiosco
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:TU_CLAVE_GENERADA_AQUI
   APP_URL=https://proyecto-empresa-web.onrender.com
   
   DB_CONNECTION=pgsql
   DATABASE_URL=<pegar-internal-database-url-aqui>
   
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   
   LOG_CHANNEL=stack
   LOG_LEVEL=info
   ```

   **Para generar APP_KEY:**
   ```bash
   php artisan key:generate --show
   ```

5. Haz clic en **"Create Web Service"**

### Paso 5: Esperar el Despliegue

El primer despliegue toma **5-10 minutos**. Render hará:

1. ✅ Clonar tu repositorio
2. ✅ Construir la imagen Docker
3. ✅ Instalar dependencias (Composer + NPM)
4. ✅ Compilar assets con Vite
5. ✅ Ejecutar migraciones
6. ✅ Optimizar la aplicación
7. ✅ Iniciar el servidor

Puedes ver el progreso en la pestaña **"Logs"**.

### Paso 6: Verificar el Despliegue

Una vez completado:

1. Haz clic en la URL de tu servicio (algo como `https://proyecto-empresa-web.onrender.com`)
2. Deberías ver la página principal del kiosco
3. Verifica que:
   - ✅ La página carga correctamente
   - ✅ Las imágenes se muestran
   - ✅ Puedes navegar entre páginas
   - ✅ El carrito funciona

### Paso 7: Ejecutar Seeders (Opcional)

Si necesitas datos de prueba:

1. Ve a **"Shell"** en el dashboard de Render
2. Ejecuta:
   ```bash
   php artisan db:seed --force
   ```

O conecta por SSH:
```bash
# Desde tu terminal local
render ssh proyecto-empresa-web
php artisan db:seed --force
```

## 🔧 Comandos Útiles en Producción

### Acceder al Shell de Render

En el dashboard → pestaña **"Shell"** o:

```bash
render ssh proyecto-empresa-web
```

### Limpiar Cachés

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Ver Logs

```bash
php artisan pail  # Si tienes Laravel Pail instalado
# O en el dashboard: pestaña "Logs"
```

### Ejecutar Migraciones

```bash
php artisan migrate --force
```

### Ejecutar Comandos Artisan

```bash
php artisan [comando] --force
```

## 🐛 Solución de Problemas

### Error: "Application key not set"

**Solución:**
1. Genera una nueva clave: `php artisan key:generate --show`
2. Copia la salida (incluyendo `base64:`)
3. En Render → Environment → Actualiza `APP_KEY`
4. Redespliega

### Error: "No se puede conectar a la base de datos"

**Solución:**
1. Verifica que la base de datos esté en la misma región
2. Verifica que `DATABASE_URL` esté configurado correctamente
3. Usa la **Internal Database URL**, no la External
4. Formato: `postgresql://user:password@host:port/database`

### Error 500 en la página

**Solución:**
1. Activa temporalmente el debug: `APP_DEBUG=true`
2. Revisa los logs en Render Dashboard
3. Verifica que todas las migraciones se ejecutaron:
   ```bash
   php artisan migrate:status
   ```
4. Verifica permisos de storage:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

### Cambios no se reflejan

**Solución:**
1. Haz commit y push de tus cambios
2. Render auto-desplegará (si está habilitado)
3. O haz clic en **"Manual Deploy"** → **"Deploy latest commit"**
4. Limpia cachés después del despliegue

### Imágenes no se cargan

**Solución:**
1. Verifica que las imágenes estén en `public/img/`
2. Verifica que estén en el repositorio Git
3. Ejecuta: `php artisan storage:link`
4. Si usas S3, configura las variables de AWS

### Build muy lento

**Solución:**
1. El plan Free de Render tiene recursos limitados
2. El primer build siempre es más lento (sin caché)
3. Builds subsecuentes usan caché de Docker
4. Considera el plan Starter ($7/mes) para mejor rendimiento

### Servicio en "Suspended"

**Solución:**
- Render suspende servicios gratuitos después de 15 minutos de inactividad
- El servicio se reactiva automáticamente en la primera solicitud
- La primera carga después de suspensión toma 30-60 segundos

## 📊 Monitoreo

### Métricas Disponibles en Render

- **CPU Usage:** Uso del procesador
- **Memory Usage:** Uso de RAM
- **Response Time:** Tiempo de respuesta
- **HTTP Requests:** Número de peticiones

### Logs

- **Deploy Logs:** Logs del proceso de build
- **Runtime Logs:** Logs de la aplicación en ejecución
- Accesibles desde el dashboard

## 🔐 Seguridad

### Checklist de Seguridad

- [ ] `APP_DEBUG=false` en producción
- [ ] `APP_KEY` único y seguro
- [ ] `.env` NO está en el repositorio
- [ ] HTTPS habilitado (automático en Render)
- [ ] Variables sensibles en Environment Variables
- [ ] Permisos de storage correctos
- [ ] Headers de seguridad configurados (ver 000-default.conf)

## 💰 Costos

### Plan Free de Render

**Web Service:**
- ✅ 750 horas/mes de compute
- ✅ HTTPS automático
- ✅ Deploy automático desde Git
- ⚠️ Se suspende después de 15 min de inactividad
- ⚠️ 512 MB RAM

**PostgreSQL:**
- ✅ 1 GB de almacenamiento
- ✅ Backups automáticos
- ⚠️ Se suspende después de 90 días sin uso

### Consideraciones:

- Para producción real, considera **Starter Plan** ($7/mes)
- Mayor RAM (512 MB → 2 GB+)
- Sin suspensión automática
- Mejor rendimiento

## 🔄 Actualizaciones Futuras

Para actualizar tu aplicación:

```bash
# 1. Haz cambios en tu código
git add .
git commit -m "Descripción de cambios"
git push

# 2. Render auto-desplegará (si está habilitado)
# O manualmente en el dashboard: "Manual Deploy"
```

## 📚 Recursos Adicionales

- [Documentación de Render](https://render.com/docs)
- [Documentación de Laravel](https://laravel.com/docs)
- [Laravel en Docker](https://laravel.com/docs/sail)
- [PostgreSQL en Render](https://render.com/docs/databases)

## ⚡ Tips de Rendimiento

1. **Optimiza assets:** `npm run build` (usa Vite)
2. **Cachea configuración:** `php artisan config:cache`
3. **Cachea rutas:** `php artisan route:cache`
4. **Cachea vistas:** `php artisan view:cache`
5. **Usa OPcache:** Ya configurado en el Dockerfile
6. **Compresión:** Habilitada en 000-default.conf
7. **CDN:** Considera usar para archivos estáticos

## 🎉 ¡Listo!

Tu aplicación debería estar funcionando en Render. Si tienes problemas, revisa:

1. Los logs en Render Dashboard
2. Esta guía de solución de problemas
3. La documentación oficial de Render

**¡Feliz despliegue! 🚀**
