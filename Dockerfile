# Imagen base de PHP con Apache
FROM php:8.2-apache

# 1. Instala dependencias del sistema.
# Usamos 'apt-get clean' en el mismo RUN para optimizar el tamaño.
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Configura e Instala las extensiones de PHP.
# Es crucial instalar pdo_pgsql aquí para que funcione la DB.
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip \
    && docker-php-ext-install pdo_pgsql

# Copia el contenido del proyecto al directorio de trabajo
COPY . /var/www/html

# Define el directorio de trabajo
WORKDIR /var/www/html

# Instala Composer (Usamos COPY --from=composer:2 como lo tienes)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instala dependencias de Laravel sin interacción
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Da permisos a Laravel para escritura
# Usamos un solo comando RUN para evitar problemas de capas
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expone el puerto 80 (Apache)
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]