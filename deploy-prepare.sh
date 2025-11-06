#!/bin/bash

# ============================================
# Script de Preparación para Despliegue
# ============================================
# Este script prepara tu proyecto para el despliegue en Render

set -e

echo "🚀 Preparando proyecto para despliegue en Render..."
echo ""

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para imprimir con color
print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ️  $1${NC}"
}

# 1. Verificar que estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    print_error "Este script debe ejecutarse desde la raíz del proyecto Laravel"
    exit 1
fi

print_success "Directorio del proyecto verificado"

# 2. Verificar Git
if [ ! -d ".git" ]; then
    print_warning "No se detectó repositorio Git"
    read -p "¿Deseas inicializar un repositorio Git? (s/n): " init_git
    if [ "$init_git" = "s" ] || [ "$init_git" = "S" ]; then
        git init
        print_success "Repositorio Git inicializado"
    fi
fi

# 3. Verificar archivos necesarios
echo ""
echo "📋 Verificando archivos necesarios..."

required_files=(
    "Dockerfile"
    "docker-entrypoint.sh"
    "render.yaml"
    ".dockerignore"
    ".env.example"
    "000-default.conf"
)

missing_files=()

for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        print_success "$file existe"
    else
        print_error "$file NO EXISTE"
        missing_files+=("$file")
    fi
done

if [ ${#missing_files[@]} -gt 0 ]; then
    print_error "Faltan archivos necesarios. Por favor, créalos antes de continuar."
    exit 1
fi

# 4. Verificar permisos del script de entrypoint
echo ""
echo "🔐 Verificando permisos..."
chmod +x docker-entrypoint.sh
print_success "Permisos de docker-entrypoint.sh configurados"

# 5. Verificar composer.json
echo ""
echo "📦 Verificando dependencias de Composer..."
if [ -f "composer.json" ]; then
    if composer validate --no-check-all --quiet; then
        print_success "composer.json es válido"
    else
        print_error "composer.json tiene errores"
        exit 1
    fi
else
    print_error "No se encontró composer.json"
    exit 1
fi

# 6. Verificar package.json
echo ""
echo "📦 Verificando dependencias de NPM..."
if [ -f "package.json" ]; then
    if node -e "JSON.parse(require('fs').readFileSync('package.json'))"; then
        print_success "package.json es válido"
    else
        print_error "package.json tiene errores"
        exit 1
    fi
else
    print_error "No se encontró package.json"
    exit 1
fi

# 7. Verificar que .env no esté en el repo
echo ""
echo "🔒 Verificando seguridad..."
if git ls-files --error-unmatch .env &> /dev/null; then
    print_error ".env está siendo rastreado por Git! Esto es un riesgo de seguridad."
    print_info "Ejecuta: git rm --cached .env"
    print_info "Y asegúrate de que .env esté en .gitignore"
else
    print_success ".env no está siendo rastreado por Git"
fi

# 8. Generar APP_KEY para mostrar
echo ""
echo "🔑 Información sobre APP_KEY..."
if [ -f ".env" ] && [ -d "vendor" ]; then
    APP_KEY=$(php artisan key:generate --show 2>/dev/null)
    if [ $? -eq 0 ]; then
        print_success "APP_KEY generada: $APP_KEY"
        print_info "Copia esta clave y úsala en Render como variable de entorno"
    else
        print_warning "No se pudo generar APP_KEY (¿faltan dependencias?)"
        print_info "Genera la clave después con: php artisan key:generate --show"
    fi
else
    print_warning "No hay .env local o vendor/ no está instalado"
    print_info "En Render, la APP_KEY se puede auto-generar"
    print_info "O genera una localmente después con: php artisan key:generate --show"
fi

# 9. Verificar estructura de directorios
echo ""
echo "📁 Verificando estructura de directorios..."
required_dirs=(
    "storage/app"
    "storage/framework/cache"
    "storage/framework/sessions"
    "storage/framework/views"
    "storage/logs"
    "bootstrap/cache"
    "public/img/productos"
    "public/img/categorias"
)

for dir in "${required_dirs[@]}"; do
    if [ -d "$dir" ]; then
        print_success "$dir existe"
    else
        print_warning "$dir no existe, creándolo..."
        mkdir -p "$dir"
        print_success "$dir creado"
    fi
done

# 10. Verificar que las imágenes existan
echo ""
echo "🖼️  Verificando imágenes..."
if [ -z "$(ls -A public/img/productos 2>/dev/null)" ]; then
    print_warning "No hay imágenes en public/img/productos"
    print_info "Asegúrate de agregar las imágenes de productos antes de desplegar"
fi

if [ -z "$(ls -A public/img/categorias 2>/dev/null)" ]; then
    print_warning "No hay imágenes en public/img/categorias"
    print_info "Asegúrate de agregar las imágenes de categorías antes de desplegar"
fi

# 11. Test de Docker (opcional)
echo ""
read -p "¿Deseas probar el build de Docker localmente? (s/n): " test_docker
if [ "$test_docker" = "s" ] || [ "$test_docker" = "S" ]; then
    print_info "Construyendo imagen Docker..."
    docker build -t proyecto-empresa-test . || {
        print_error "El build de Docker falló"
        exit 1
    }
    print_success "Imagen Docker construida exitosamente"
    
    print_info "Para probar la imagen, ejecuta:"
    echo "docker run -p 8000:80 -e DB_CONNECTION=sqlite proyecto-empresa-test"
fi

# 12. Checklist final
echo ""
echo "============================================"
echo "📋 CHECKLIST PARA DESPLIEGUE EN RENDER"
echo "============================================"
echo ""
echo "Antes de desplegar, asegúrate de:"
echo ""
echo "✓ Tu código está en un repositorio Git (GitHub/GitLab)"
echo "✓ Has hecho commit de todos los cambios"
echo "✓ El archivo .env NO está en el repositorio"
echo "✓ Has verificado que render.yaml esté configurado correctamente"
echo "✓ Tienes las imágenes de productos y categorías"
echo ""
echo "En Render, deberás:"
echo ""
echo "1. Conectar tu repositorio"
echo "2. Seleccionar 'Blueprint' para usar render.yaml"
echo "3. Verificar que las variables de entorno estén configuradas"
echo "4. Esperar a que el build termine (5-10 minutos)"
echo ""
echo "Variables de entorno importantes:"
echo "  - APP_KEY (genera con: php artisan key:generate --show)"
echo "  - APP_URL (tu URL de Render)"
echo "  - DB_* (se configuran automáticamente desde la base de datos)"
echo ""
print_success "¡Proyecto listo para despliegue!"
echo ""
