# 🚀 Proyecto Empresa - Kiosco Laravel

Sistema de kiosco digital para gestión de pedidos desarrollado con Laravel 12.

## 📋 Requisitos

- PHP >= 8.2
- PostgreSQL >= 14
- Composer
- Node.js >= 18
- Docker (opcional pero recomendado)

## 🏗️ Tecnologías

- **Framework:** Laravel 12
- **Base de datos:** PostgreSQL
- **Frontend:** Tailwind CSS 4 + Vite
- **QR Codes:** SimpleSoftwareIO Simple QR Code

## 🐳 Desarrollo Local con Docker

### 1. Clonar el repositorio

```bash
git clone <tu-repositorio>
cd Proyecto-Empresa
```

### 2. Iniciar contenedores

```bash
docker-compose up -d
```

Esto iniciará:
- **App Laravel:** http://localhost:8000
- **PostgreSQL:** localhost:5432
- **Adminer:** http://localhost:8080

### 3. Instalar dependencias (primera vez)

```bash
# Entrar al contenedor
docker-compose exec app bash

# Dentro del contenedor
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
```

### 4. Acceder a la aplicación

Abre tu navegador en: http://localhost:8000

## 💻 Desarrollo Local sin Docker

### 1. Instalar dependencias

```bash
composer install
npm install
```

### 2. Configurar entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configurar base de datos

Edita `.env` con tus credenciales de PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=proyecto_empresa
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Ejecutar migraciones

```bash
php artisan migrate --seed
```

### 5. Compilar assets

```bash
npm run dev  # Para desarrollo
npm run build  # Para producción
```

### 6. Iniciar servidor

```bash
php artisan serve
```

## 🌐 Despliegue en Render

### Opción 1: Despliegue Automático (Recomendado)

1. **Conecta tu repositorio a Render:**
   - Ve a https://dashboard.render.com
   - Crea una nueva cuenta o inicia sesión
   - Haz clic en "New +" → "Blueprint"
   - Conecta tu repositorio de GitHub/GitLab
   - Render detectará automáticamente el archivo `render.yaml`

2. **Configura las variables de entorno:**
   - Render creará automáticamente la base de datos PostgreSQL
   - Se generará automáticamente `APP_KEY`
   - Todas las variables de DB_* se configurarán automáticamente

3. **Despliega:**
   - Haz clic en "Apply"
   - Espera a que el build termine (5-10 minutos en el primer deploy)
   - Tu aplicación estará disponible en: `https://proyecto-empresa-web.onrender.com`

### Opción 2: Despliegue Manual

1. **Crear Base de Datos:**
   - En Render Dashboard: "New +" → "PostgreSQL"
   - Nombre: `proyecto-empresa-db`
   - Plan: Free
   - Crea la base de datos

2. **Crear Web Service:**
   - "New +" → "Web Service"
   - Conecta tu repositorio
   - Runtime: Docker
   - Plan: Free

3. **Configurar Variables de Entorno:**
   ```
   APP_NAME=CorporacionOrganicaKiosco
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:... (generar con: php artisan key:generate --show)
   APP_URL=https://tu-app.onrender.com
   
   DB_CONNECTION=pgsql
   DATABASE_URL=<copiar desde la base de datos creada>
   ```

4. **Desplegar:**
   - Haz clic en "Create Web Service"
   - Render construirá automáticamente tu imagen Docker

## 📦 Estructura del Proyecto

```
├── app/
│   ├── Http/
│   │   └── Controllers/     # Controladores (Catalogo, Pedido, Producto)
│   └── Models/              # Modelos Eloquent
├── database/
│   ├── migrations/          # Migraciones de base de datos
│   └── seeders/            # Seeders (Categorías, Productos)
├── public/                 # Archivos públicos
│   ├── css/
│   └── img/
│       ├── categorias/     # Imágenes de categorías
│       └── productos/      # Imágenes de productos
├── resources/
│   ├── views/              # Vistas Blade
│   │   ├── admin/          # Panel de administración
│   │   ├── catalogo/       # Vistas del kiosco
│   │   └── layouts/        # Layouts
│   ├── css/                # Estilos (Tailwind)
│   └── js/                 # JavaScript
├── routes/
│   └── web.php             # Rutas de la aplicación
├── Dockerfile              # Configuración Docker
├── docker-compose.yml      # Docker Compose para desarrollo
├── render.yaml             # Configuración de Render
└── docker-entrypoint.sh    # Script de inicio
```

## 🔧 Comandos Útiles

### Laravel

```bash
# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
php artisan migrate
php artisan migrate:fresh --seed  # Limpia y vuelve a crear

# Crear enlace simbólico para storage
php artisan storage:link
```

### Docker

```bash
# Ver logs
docker-compose logs -f app

# Reiniciar servicios
docker-compose restart

# Detener todo
docker-compose down

# Reconstruir imagen
docker-compose build --no-cache

# Ejecutar comandos en el contenedor
docker-compose exec app php artisan migrate
```

## 🎨 Características

- 📱 Interfaz de kiosco táctil
- 🛒 Sistema de carrito de compras
- 📦 Gestión de pedidos en tiempo real
- 👨‍🍳 Panel de cocina
- 🏷️ Gestión de productos y categorías
- 📊 Panel de administración
- 🖼️ Carga de imágenes de productos
- 📱 Diseño responsive

## 🐛 Troubleshooting

### Error: "No application encryption key"
```bash
php artisan key:generate
```

### Error de permisos en storage
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Error de conexión a base de datos en Render
- Verifica que DATABASE_URL esté configurado correctamente
- Asegúrate de que la base de datos esté en la misma región que el web service

### Build falla en Render
- Revisa los logs de build en Render Dashboard
- Verifica que todas las dependencias estén en composer.json
- Asegúrate de que el Dockerfile sea correcto

## 📝 Notas de Producción

- **Sesiones:** Configuradas para usar base de datos (más estable en Render)
- **Cache:** Usa base de datos (Redis no disponible en plan free)
- **Queue:** Usa base de datos
- **Storage:** Archivos locales (considera S3 para producción escalable)
- **Logs:** Configurados en modo single para mejor rendimiento

## 🔐 Seguridad

- Siempre mantén `APP_DEBUG=false` en producción
- Genera una nueva `APP_KEY` para cada entorno
- No commits el archivo `.env` al repositorio
- Usa HTTPS en producción (Render lo proporciona automáticamente)

## 📄 Licencia

Este proyecto es privado y confidencial.

## 👥 Autor

Desarrollado para Corporación Orgánica Kiosco
