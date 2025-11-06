# ============================================
# Multi-stage Dockerfile para Laravel en Render
# ============================================

# Stage 1: Builder - Instala dependencias
FROM php:8.2-apache AS builder

# Instala dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Configura e instala extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd zip mbstring opcache

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establece directorio de trabajo
WORKDIR /var/www/html

# Copia TODO el código primero
COPY . .

# Instala dependencias de PHP (CON dev dependencies para el build)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Instala dependencias de Node
RUN npm ci 2>/dev/null || npm install

# Copia .env.example a .env para build
RUN cp .env.example .env

# Genera la clave de aplicación
RUN php artisan key:generate --force

# Construye los assets con Vite
RUN npm run build

# AHORA elimina las dependencias de dev para reducir tamaño
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Limpia archivos innecesarios
RUN rm -rf node_modules tests

# ============================================
# Stage 2: Runtime - Imagen final optimizada
# ============================================
FROM php:8.2-apache

# Instala solo las dependencias necesarias para runtime
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Instala extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd zip mbstring opcache

# Configura PHP para producción
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Configura OPcache para mejor rendimiento
RUN echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.memory_consumption=256" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.max_accelerated_files=20000" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.validate_timestamps=0" >> "$PHP_INI_DIR/conf.d/opcache.ini"

# Habilita módulos de Apache necesarios
RUN a2enmod rewrite headers

# Copia configuración personalizada de Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Establece directorio de trabajo
WORKDIR /var/www/html

# Copia archivos desde el builder
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# Crea directorios necesarios y establece permisos
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Expone puerto (Render usa variable PORT, pero Apache usa 80 internamente)
EXPOSE 80

# Script de inicio para configurar en runtime
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]