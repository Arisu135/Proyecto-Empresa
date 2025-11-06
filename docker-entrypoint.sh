#!/bin/bash
set -e

echo "🚀 Iniciando aplicación Laravel..."

# Espera a que la base de datos esté disponible
echo "⏳ Esperando a la base de datos..."
max_attempts=30
attempt=0

while [ $attempt -lt $max_attempts ]; do
    if php artisan db:show > /dev/null 2>&1; then
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

# Ejecuta migraciones
echo "🔄 Ejecutando migraciones..."
php artisan migrate --force --no-interaction || {
    echo "⚠️  Error en migraciones, pero continuando..."
}

# Ejecuta seeders (opcional, comenta si no quieres ejecutar en cada deploy)
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
