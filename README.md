# Proyecto Empresa - Kiosco Digital 🏪

Sistema de kiosco digital para gestión de pedidos desarrollado con Laravel 12, diseñado para ser desplegado en Render usando Docker.

## 🚀 Inicio Rápido

### Deploy Rápido (Recomendado)

```bash
# Deploy instantáneo con Deploy Hook
deploy.bat  # Windows
./deploy.sh # Linux/Mac
```

📖 **[Guía de Deploy Rápido](./DEPLOY.md)**

### Despliegue Completo en Render

```bash
# 1. Hacer cambios y commitear
git add .
git commit -m "Descripción"
git push

# 2. Deploy automático
deploy.bat
```

📖 **[Guía Completa de Despliegue](./RENDER_GUIDE.md)**

### Desarrollo Local con Docker

```bash
# Iniciar proyecto
./local-test.sh

# Acceder a:
# - App: http://localhost:8000
# - Adminer: http://localhost:8080
```

### Desarrollo Local sin Docker

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

## 📚 Documentación

- **[DEPLOY.md](./DEPLOY.md)** - Deploy rápido con Deploy Hook ⚡
- **[RENDER_GUIDE.md](./RENDER_GUIDE.md)** - Guía completa de despliegue en Render
- **[DEPLOYMENT.md](./DEPLOYMENT.md)** - Documentación técnica y troubleshooting

## 🛠️ Stack Tecnológico

- **Backend:** Laravel 12 (PHP 8.2)
- **Base de datos:** PostgreSQL 16
- **Frontend:** Tailwind CSS 4 + Vite
- **Contenedor:** Docker
- **Hosting:** Render.com
- **Servidor Web:** Apache 2.4

## 📁 Estructura del Proyecto

```
├── app/                    # Código de la aplicación
│   ├── Http/Controllers/   # Controladores
│   └── Models/            # Modelos Eloquent
├── database/
│   ├── migrations/        # Migraciones de BD
│   └── seeders/          # Datos de prueba
├── public/               # Archivos públicos
│   ├── img/             # Imágenes
│   └── css/             # Estilos compilados
├── resources/
│   ├── views/           # Vistas Blade
│   ├── css/             # CSS (Tailwind)
│   └── js/              # JavaScript
├── Dockerfile           # Configuración Docker
├── docker-compose.yml   # Docker Compose (dev)
├── render.yaml          # Configuración Render
└── deploy-prepare.sh    # Script de preparación
```

## 🎯 Características

✅ **Kiosco Digital**
- Catálogo de productos por categorías
- Carrito de compras interactivo
- Sistema de pedidos en tiempo real
- Códigos QR para pedidos

✅ **Panel de Administración**
- Gestión de productos
- Gestión de pedidos
- Panel de cocina
- Estados de pedido

✅ **Sistema de Tickets** 🎫
- Impresión automática de 2 tickets al confirmar pedido:
  - 🍳 Ticket Cocina (productos y opciones)
  - 💰 Ticket Caja (precios y total)
- Agregar productos a pedidos existentes desde Caja
- Diseño optimizado para impresoras térmicas 80mm
- Compatible con Xprinter XP-Q260NL (ESC/POS)

📝 **[Ver Guía de Tickets](./TICKETS_FEATURE.md)**
🖨️ **[Configurar Impresora](./IMPRESORA_CONFIG.md)**

✅ **Optimizado para Producción**
- Docker multi-stage build
- OPcache configurado
- Assets compilados con Vite
- Cache de rutas y configuración
- Headers de seguridad

## 🔧 Scripts Útiles

```bash
# Deploy rápido a Render
deploy.bat              # Windows
./deploy.sh             # Linux/Mac

# Preparar para despliegue
./deploy-prepare.sh

# Probar localmente con Docker
./local-test.sh

# Comandos Laravel
php artisan migrate          # Ejecutar migraciones
php artisan db:seed         # Cargar datos de prueba
php artisan cache:clear     # Limpiar caché
php artisan config:cache    # Cachear configuración

# Docker
docker-compose up -d        # Iniciar contenedores
docker-compose down         # Detener contenedores
docker-compose logs -f app  # Ver logs
```

## 🌍 Variables de Entorno

### Producción (Render)

```env
APP_NAME=CorporacionOrganicaKiosco
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
APP_URL=https://tu-app.onrender.com

DB_CONNECTION=pgsql
DATABASE_URL=<auto-configurado-por-render>

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Desarrollo Local

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=localhost  # o 'postgres' si usas docker-compose
DB_PORT=5432
DB_DATABASE=proyecto_empresa
DB_USERNAME=postgres
DB_PASSWORD=secret
```

## 🔐 Seguridad

- ✅ `.env` excluido del repositorio
- ✅ APP_DEBUG=false en producción
- ✅ Headers de seguridad configurados
- ✅ HTTPS automático en Render
- ✅ Variables sensibles en Environment Variables
- ✅ CSRF protection habilitado
- ✅ SQL injection prevention (Eloquent ORM)

## 🐛 Troubleshooting

### Error: "No application encryption key"
```bash
php artisan key:generate
```

### Error de permisos
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Imágenes no se cargan
```bash
php artisan storage:link
```

### Cambios no se reflejan
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

📖 **Ver [RENDER_GUIDE.md](./RENDER_GUIDE.md) para más soluciones**

## 📊 Estado del Proyecto

- ✅ Configuración de Docker completada
- ✅ Configuración de Render completada
- ✅ Scripts de despliegue creados
- ✅ Documentación completa
- ✅ Optimizaciones de producción aplicadas
- ✅ Seguridad configurada

## 🚀 Próximos Pasos

1. **Revisar y actualizar seeders** si necesitas datos específicos
2. **Agregar imágenes** de productos y categorías en `public/img/`
3. **Ejecutar** `./deploy-prepare.sh` para verificar
4. **Probar localmente** con `./local-test.sh`
5. **Desplegar en Render** siguiendo [RENDER_GUIDE.md](./RENDER_GUIDE.md)

## 📞 Soporte

Si tienes problemas:
1. Consulta [RENDER_GUIDE.md](./RENDER_GUIDE.md) - Solución de problemas
2. Revisa los logs en Render Dashboard
3. Verifica las variables de entorno
4. Prueba localmente con Docker primero

## 📄 Licencia

Este proyecto es privado y confidencial.

---

**Desarrollado para Corporación Orgánica Kiosco**

*Construido con Laravel 12*
