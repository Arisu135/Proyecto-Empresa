# 🚀 Guía Rápida de Comandos

Referencia rápida de comandos para desarrollo y despliegue.

## 📦 Instalación y Setup

```bash
# Clonar repositorio
git clone <tu-repositorio>
cd Proyecto-Empresa

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Base de datos
php artisan migrate
php artisan db:seed

# Compilar assets
npm run build        # Producción
npm run dev         # Desarrollo

# Iniciar servidor
php artisan serve
```

## 🐳 Docker (Desarrollo Local)

```bash
# Iniciar todo (setup automático)
./local-test.sh

# O manualmente:
docker-compose up -d                    # Iniciar
docker-compose down                     # Detener
docker-compose restart                  # Reiniciar
docker-compose build --no-cache        # Reconstruir

# Ver logs
docker-compose logs -f app
docker-compose logs -f postgres

# Ejecutar comandos
docker-compose exec app bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app composer install
```

## 🚀 Despliegue

```bash
# Preparar para despliegue
./deploy-prepare.sh

# Subir a Git
git add .
git commit -m "Mensaje"
git push origin main

# En Render: automático si está configurado
# O manualmente: Dashboard → Manual Deploy
```

## 🗄️ Base de Datos

```bash
# Migraciones
php artisan migrate                    # Ejecutar migraciones
php artisan migrate:fresh              # Limpiar y recrear
php artisan migrate:fresh --seed       # Con seeders
php artisan migrate:rollback           # Revertir última
php artisan migrate:status             # Ver estado

# Seeders
php artisan db:seed                    # Todos
php artisan db:seed --class=CategoriaSeeder  # Específico

# Info de BD
php artisan db:show                    # Info general
php artisan db:table users            # Info de tabla
```

## 🧹 Limpiar Cachés

```bash
# Limpiar todo
php artisan optimize:clear

# Individual
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
```

## ⚡ Optimizar (Producción)

```bash
# Optimizar todo
php artisan optimize

# Individual
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Composer
composer install --optimize-autoloader --no-dev
```

## 🔐 Storage y Permisos

```bash
# Enlace simbólico
php artisan storage:link

# Permisos (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Crear directorios
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
```

## 🔧 Artisan (Otros)

```bash
# Ver todas las rutas
php artisan route:list

# Crear componentes
php artisan make:controller NombreController
php artisan make:model Nombre -m          # Con migración
php artisan make:migration nombre_tabla
php artisan make:seeder NombreSeeder

# Cola (Queue)
php artisan queue:work
php artisan queue:listen
php artisan queue:restart

# Tinker (consola)
php artisan tinker
```

## 📊 Git

```bash
# Estado
git status
git log --oneline

# Commits
git add .
git add archivo.php
git commit -m "Mensaje"
git commit --amend                     # Modificar último commit

# Branches
git branch                             # Ver branches
git branch nombre                      # Crear branch
git checkout nombre                    # Cambiar branch
git checkout -b nombre                 # Crear y cambiar

# Remote
git remote -v                          # Ver remotes
git fetch origin                       # Traer cambios
git pull origin main                   # Pull
git push origin main                   # Push

# Deshacer
git reset --hard HEAD                  # Deshacer todo
git reset HEAD archivo.php             # Unstage archivo
git checkout -- archivo.php            # Deshacer cambios
```

## 🌐 Render (Producción)

```bash
# Acceder por SSH
render ssh proyecto-empresa-web

# Una vez dentro:
php artisan migrate --force
php artisan db:seed --force
php artisan cache:clear
php artisan config:cache
php artisan storage:link

# Ver logs (desde local con Render CLI)
render logs proyecto-empresa-web

# Desplegar manualmente
# Dashboard → Services → proyecto-empresa-web → Manual Deploy
```

## 🔍 Debug y Logs

```bash
# Ver logs
tail -f storage/logs/laravel.log
tail -n 100 storage/logs/laravel.log   # Últimas 100 líneas

# Logs en tiempo real
php artisan pail                        # Si tienes Pail instalado

# En Docker
docker-compose logs -f app
```

## 🧪 Testing

```bash
# Ejecutar tests
php artisan test
php artisan test --filter NombreTest

# Con PHPUnit directamente
./vendor/bin/phpunit
```

## 📝 NPM/Vite

```bash
# Desarrollo
npm run dev                            # Watch mode

# Producción
npm run build                          # Compilar assets

# Limpiar
rm -rf node_modules
npm install                            # Reinstalar
```

## 🔑 Generar Claves

```bash
# APP_KEY
php artisan key:generate               # Actualiza .env
php artisan key:generate --show        # Solo muestra

# Password hash
php artisan tinker
>>> bcrypt('mi_password')
```

## 📦 Composer

```bash
# Instalar
composer install
composer install --no-dev              # Sin dev dependencies

# Actualizar
composer update
composer update paquete/nombre         # Específico

# Autoload
composer dump-autoload
```

## 🛠️ Troubleshooting Rápido

```bash
# Error "Class not found"
composer dump-autoload

# Error de permisos
chmod -R 775 storage bootstrap/cache

# Error "No application encryption key"
php artisan key:generate

# Error con DB
php artisan migrate:fresh
php artisan db:show

# Limpiar todo
php artisan optimize:clear
composer dump-autoload
php artisan optimize
```

## 🎯 One-Liners Útiles

```bash
# Setup completo rápido
composer install && npm install && cp .env.example .env && php artisan key:generate && php artisan migrate --seed

# Limpiar y optimizar
php artisan optimize:clear && php artisan optimize

# Rebuild completo Docker
docker-compose down && docker-compose build --no-cache && docker-compose up -d

# Ver qué está usando el puerto 8000
lsof -ti:8000 | xargs kill -9          # Mac/Linux
```

## 📊 Información del Sistema

```bash
# Versiones
php -v
composer -V
node -v
npm -v
docker -v

# Laravel
php artisan --version
php artisan about

# Extensiones PHP
php -m

# Configuración PHP
php -i | grep "Configuration File"
```

## 🔄 Workflow Típico

```bash
# 1. Iniciar desarrollo
git pull origin main
composer install
npm install
php artisan migrate

# 2. Desarrollar
npm run dev                            # En terminal 1
php artisan serve                      # En terminal 2

# 3. Antes de commit
php artisan test
npm run build
php artisan optimize

# 4. Deploy
git add .
git commit -m "Descripción"
git push origin main
```

## ⚠️ Importante Recordar

```bash
# NUNCA subir .env a Git
git rm --cached .env                   # Si ya está tracked

# SIEMPRE probar antes de push
php artisan test
npm run build

# SIEMPRE limpiar caché después de cambios de config
php artisan config:clear
php artisan config:cache
```

---

**💡 Tip:** Guarda este archivo en favoritos para consulta rápida!
