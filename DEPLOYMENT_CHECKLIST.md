# ✅ Checklist de Despliegue - Proyecto Empresa

Usa este checklist antes de desplegar tu aplicación en Render.

## 📋 Pre-despliegue (Local)

### Configuración del Proyecto

- [ ] Todo el código está commiteado en Git
- [ ] El archivo `.env` NO está en el repositorio
- [ ] `.gitignore` está configurado correctamente
- [ ] `composer.json` y `package.json` son válidos
- [ ] Todas las dependencias están declaradas correctamente

### Archivos Necesarios

- [ ] `Dockerfile` existe y está configurado
- [ ] `docker-entrypoint.sh` existe y tiene permisos de ejecución
- [ ] `render.yaml` existe y está configurado
- [ ] `.dockerignore` existe
- [ ] `000-default.conf` existe
- [ ] `.env.example` está actualizado

### Estructura de Directorios

- [ ] `storage/app/` existe
- [ ] `storage/framework/cache/` existe
- [ ] `storage/framework/sessions/` existe
- [ ] `storage/framework/views/` existe
- [ ] `storage/logs/` existe
- [ ] `bootstrap/cache/` existe
- [ ] `public/img/productos/` existe
- [ ] `public/img/categorias/` existe

### Base de Datos

- [ ] Todas las migraciones están creadas
- [ ] Las migraciones se ejecutan sin errores
- [ ] Los seeders funcionan correctamente
- [ ] Las relaciones entre tablas están correctas
- [ ] No hay datos hardcodeados que deban estar en variables de entorno

### Assets y Frontend

- [ ] `npm run build` se ejecuta sin errores
- [ ] Los assets se compilan correctamente
- [ ] Las imágenes están en las carpetas correctas
- [ ] Los estilos CSS se cargan correctamente
- [ ] JavaScript funciona sin errores en la consola

### Pruebas Locales

- [ ] La aplicación funciona en desarrollo local
- [ ] Has probado todas las rutas principales
- [ ] El carrito de compras funciona
- [ ] Los pedidos se crean correctamente
- [ ] El panel de administración es accesible
- [ ] Las imágenes se cargan correctamente

### Scripts de Verificación

- [ ] Has ejecutado `./deploy-prepare.sh`
- [ ] Todos los checks pasaron exitosamente
- [ ] (Opcional) Has probado con `./local-test.sh`

## 🌐 Configuración en Render

### Cuenta y Repositorio

- [ ] Tienes cuenta en Render.com
- [ ] Tu repositorio está en GitHub/GitLab/Bitbucket
- [ ] El repositorio es accesible (público o con permisos)
- [ ] Has hecho push del código más reciente

### Base de Datos PostgreSQL

- [ ] Base de datos PostgreSQL creada
- [ ] Nombre: `proyecto-empresa-db`
- [ ] Plan: Free (o el que necesites)
- [ ] Región seleccionada
- [ ] Has copiado la Internal Database URL

### Web Service

- [ ] Web Service creado
- [ ] Runtime: Docker
- [ ] Branch correcto seleccionado
- [ ] Región coincide con la base de datos
- [ ] Plan seleccionado (Free recomendado para empezar)

### Variables de Entorno

- [ ] `APP_NAME` configurado
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` generado y configurado
- [ ] `APP_URL` con tu URL de Render
- [ ] `DB_CONNECTION=pgsql`
- [ ] `DATABASE_URL` configurado (desde la base de datos)
- [ ] `DB_HOST` configurado (auto desde DB)
- [ ] `DB_PORT` configurado (auto desde DB)
- [ ] `DB_DATABASE` configurado (auto desde DB)
- [ ] `DB_USERNAME` configurado (auto desde DB)
- [ ] `DB_PASSWORD` configurado (auto desde DB)
- [ ] `SESSION_DRIVER=database`
- [ ] `CACHE_STORE=database`
- [ ] `QUEUE_CONNECTION=database`
- [ ] `LOG_CHANNEL=stack`
- [ ] `LOG_LEVEL=info`

### Configuración Adicional (Opcional)

- [ ] Variables de mail configuradas (si usas email)
- [ ] Variables de AWS configuradas (si usas S3)
- [ ] Variables personalizadas de tu aplicación

## 🚀 Durante el Despliegue

### Proceso de Build

- [ ] El build ha iniciado en Render
- [ ] Monitoreas los logs de build
- [ ] No hay errores de Dockerfile
- [ ] Composer install exitoso
- [ ] NPM install exitoso
- [ ] Assets compilados con Vite
- [ ] Build completado exitosamente

### Deploy

- [ ] El deploy ha iniciado
- [ ] El contenedor arrancó correctamente
- [ ] Las migraciones se ejecutaron
- [ ] La aplicación está "Live"

## ✅ Post-despliegue

### Verificación Básica

- [ ] La URL de Render está activa
- [ ] La página principal carga
- [ ] No hay errores 500
- [ ] No hay errores en la consola del navegador
- [ ] Los estilos se cargan correctamente

### Funcionalidad

- [ ] Puedes navegar entre páginas
- [ ] El catálogo de productos se muestra
- [ ] Las imágenes se cargan
- [ ] El carrito funciona
- [ ] Puedes crear un pedido
- [ ] El panel de admin es accesible
- [ ] HTTPS está activo

### Base de Datos

- [ ] La conexión a la base de datos funciona
- [ ] Las tablas existen
- [ ] Puedes crear, leer, actualizar y eliminar datos

### Optimización

- [ ] Los cachés están funcionando
- [ ] La aplicación responde rápidamente
- [ ] No hay memory leaks evidentes

### Monitoreo

- [ ] Has revisado los logs en Render
- [ ] No hay errores críticos en los logs
- [ ] Las métricas se ven normales

## 🔒 Seguridad

### Configuración

- [ ] `APP_DEBUG=false` en producción
- [ ] `.env` no está expuesto
- [ ] HTTPS está activo
- [ ] Headers de seguridad configurados

### Pruebas de Seguridad

- [ ] No puedes acceder a archivos sensibles
- [ ] Las rutas de admin están protegidas (si aplica)
- [ ] CSRF protection funciona
- [ ] No hay información sensible en los logs públicos

## 📝 Documentación

- [ ] Has documentado la URL de producción
- [ ] Has guardado las credenciales de Render
- [ ] Has documentado variables de entorno importantes
- [ ] El equipo sabe cómo acceder a los logs

## 🎯 Opcional pero Recomendado

### Seeders

- [ ] Has ejecutado los seeders si necesitas datos iniciales
- [ ] Los productos de prueba están cargados
- [ ] Las categorías están configuradas

### Monitoreo

- [ ] Has configurado alertas en Render (si aplica)
- [ ] Tienes un sistema para revisar logs regularmente

### Backups

- [ ] Entiendes cómo funciona el backup de Render
- [ ] Has verificado la política de backups
- [ ] Sabes cómo restaurar la base de datos

### Performance

- [ ] Has probado la velocidad de carga
- [ ] Has verificado el tiempo de respuesta
- [ ] La aplicación funciona bien con múltiples usuarios

## 🐛 Si Algo Sale Mal

### Troubleshooting Rápido

- [ ] Revisa los logs de Render
- [ ] Verifica las variables de entorno
- [ ] Confirma que la base de datos está activa
- [ ] Revisa la [Guía de Render](./RENDER_GUIDE.md)
- [ ] Prueba localmente con Docker

### Rollback

- [ ] Sabes cómo hacer rollback a una versión anterior
- [ ] Tienes backup del código anterior
- [ ] Puedes restaurar la base de datos si es necesario

---

## 📊 Resumen de Estado

```
Total de items: ~100
Items completados: ___ / 100

Porcentaje: ___%
```

**Estado del despliegue:** [ ] 🟢 Listo | [ ] 🟡 En progreso | [ ] 🔴 Bloqueado

**Notas adicionales:**
```
[Escribe aquí cualquier nota importante sobre el despliegue]
```

---

**Fecha de despliegue:** _______________

**Desplegado por:** _______________

**URL de producción:** _______________

**Versión:** _______________

---

✅ = Completado | ⏳ = En progreso | ❌ = Bloqueado | ⚠️ = Requiere atención
