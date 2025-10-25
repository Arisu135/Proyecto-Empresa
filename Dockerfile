# Usa PHP 8.2 con Apache
FROM php:8.2-apache

# Instala extensiones necesarias para Laravel y PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip && \
    docker-php-ext-install pdo pdo_pgsql zip

# Copia el código al contenedor
COPY . /var/www/html

# Define el directorio de trabajo
WORKDIR /var/www/html

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instala dependencias
RUN composer install --no-dev --optimize-autoloader

# Expone el puerto 10000 (Render lo usa internamente)
EXPOSE 10000

# Comando de inicio de Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000
