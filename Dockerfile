# Etapa base: PHP + Composer + dependencias de Laravel
FROM php:8.2-fpm

# Instala dependencias del sistema y extensiones necesarias
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia el código de la aplicación
WORKDIR /var/www/html
COPY . .

# Instala dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Genera clave de aplicación y cachea la configuración
RUN php artisan key:generate && php artisan config:cache

# Expone el puerto 10000 (Render usa este por defecto)
EXPOSE 10000

# Comando para iniciar Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000
