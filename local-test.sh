#!/bin/bash

# ============================================
# Script de Prueba Local con Docker
# ============================================
# Este script levanta el proyecto localmente para probar antes del despliegue

set -e

echo "🐳 Iniciando prueba local con Docker..."
echo ""

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ️  $1${NC}"
}

# 1. Verificar que Docker esté instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker no está instalado"
    echo "Instala Docker desde: https://www.docker.com/products/docker-desktop"
    exit 1
fi

print_success "Docker está instalado"

# 2. Verificar que docker-compose esté instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ docker-compose no está instalado"
    exit 1
fi

print_success "docker-compose está instalado"

# 3. Detener contenedores existentes
print_info "Deteniendo contenedores existentes..."
docker-compose down 2>/dev/null || true

# 4. Construir las imágenes
print_info "Construyendo imágenes Docker..."
docker-compose build --no-cache

print_success "Imágenes construidas"

# 5. Iniciar los contenedores
print_info "Iniciando contenedores..."
docker-compose up -d

print_success "Contenedores iniciados"

# 6. Esperar a que la base de datos esté lista
print_info "Esperando a que PostgreSQL esté listo..."
sleep 10

# 7. Instalar dependencias
print_info "Instalando dependencias de Composer..."
docker-compose exec -T app composer install --no-interaction --prefer-dist --optimize-autoloader

# 8. Configurar .env
print_info "Configurando .env..."
docker-compose exec -T app cp .env.example .env || true
docker-compose exec -T app php artisan key:generate

# 9. Ejecutar migraciones
print_info "Ejecutando migraciones..."
docker-compose exec -T app php artisan migrate --force

# 10. Ejecutar seeders (opcional)
read -p "¿Deseas ejecutar los seeders? (s/n): " run_seeders
if [ "$run_seeders" = "s" ] || [ "$run_seeders" = "S" ]; then
    print_info "Ejecutando seeders..."
    docker-compose exec -T app php artisan db:seed --force
fi

# 11. Crear enlace simbólico
print_info "Creando enlace simbólico de storage..."
docker-compose exec -T app php artisan storage:link || true

# 12. Compilar assets
print_info "Instalando dependencias de NPM..."
docker-compose exec -T app npm install

print_info "Compilando assets..."
docker-compose exec -T app npm run build

# 13. Optimizar aplicación
print_info "Optimizando aplicación..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

print_success "¡Aplicación lista!"
echo ""
echo "============================================"
echo "🎉 PROYECTO LEVANTADO EXITOSAMENTE"
echo "============================================"
echo ""
echo "Accede a la aplicación en:"
echo "  🌐 Laravel App: http://localhost:8000"
echo "  🗄️  Adminer (DB): http://localhost:8080"
echo ""
echo "Credenciales de la base de datos:"
echo "  Sistema: PostgreSQL"
echo "  Servidor: postgres"
echo "  Usuario: postgres"
echo "  Contraseña: secret"
echo "  Base de datos: proyecto_empresa"
echo ""
echo "Comandos útiles:"
echo "  Ver logs: docker-compose logs -f app"
echo "  Entrar al contenedor: docker-compose exec app bash"
echo "  Detener: docker-compose down"
echo "  Reiniciar: docker-compose restart"
echo ""
print_success "¡Prueba tu aplicación antes de desplegar en Render!"
echo ""
