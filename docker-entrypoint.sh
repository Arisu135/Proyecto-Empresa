#!/bin/bash
set -e

echo "🚀 Iniciando aplicación Laravel..."

# Configura Apache para usar el puerto correcto (Render usa $PORT)
if [ -n "$PORT" ]; then
    echo "🔧 Configurando Apache para puerto $PORT..."
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf
else
    echo "⚠️  Variable PORT no definida, usando puerto 80 por defecto"
fi

# Espera a que la base de datos esté disponible
echo "⏳ Esperando a la base de datos..."
max_attempts=30
attempt=0

# Espera a que PostgreSQL acepte conexiones
while [ $attempt -lt $max_attempts ]; do
    if nc -z ${DB_HOST:-postgres} ${DB_PORT:-5432} 2>/dev/null; then
        echo "✅ Base de datos disponible"
        break
    fi
    attempt=$((attempt + 1))
    echo "⏳ Intento $attempt de $max_attempts..."
    sleep 2
done

if [ $attempt -eq $max_attempts ]; then
    echo "❌ No se pudo conectar a la base de datos después de $max_attempts intentos"
    echo "⚠️  Continuando de todos modos..."
fi

# Limpia cache de paquetes primero
echo "🧹 Limpiando cache de paquetes..."
rm -rf /var/www/html/bootstrap/cache/packages.php
rm -rf /var/www/html/bootstrap/cache/services.php

# DEBUG: Mostrar información PHP y drivers PDO instalados (útil para detectar pdo_pgsql faltante)
echo "🔍 Información PHP y extensiones relevantes:"
php -v || true
echo "--- módulos PHP (filtrados) ---"
php -m | grep -Ei "pdo|pgsql|pdo_pgsql" || php -m || true
echo "--- PDO drivers disponibles ---"
php -r "print_r(PDO::getAvailableDrivers());" || true
echo "--------------------------------"

# Ejecuta migraciones
echo "🔄 Ejecutando migraciones..."
php artisan migrate --force --no-interaction || {
    echo "⚠️  Error en migraciones, pero continuando..."
}

# Seeders desactivados - datos cargados manualmente en Neon
# echo "🌱 Ejecutando seeders..."
# php artisan db:seed --force --no-interaction || echo "⚠️  Seeders no ejecutados"

# Limpia y optimiza la aplicación
echo "🧹 Limpiando caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "⚡ Optimizando para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crea el enlace simbólico de storage
echo "🔗 Creando enlace simbólico de storage..."
php artisan storage:link || echo "⚠️  El enlace ya existe"

# Asegura permisos correctos
echo "🔐 Configurando permisos..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Aplicación lista!"
echo "🌐 Iniciando Apache..."

# Ejecuta el comando pasado al contenedor
exec "$@"
