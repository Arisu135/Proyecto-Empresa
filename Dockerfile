# Imagen base de PHP con Apache
FROM php:8.2-apache

# 1. Instala dependencias del sistema y extensiones (incluye pdo_pgsql)
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
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip \
    && docker-php-ext-install pdo_pgsql

# 3. CONFIGURACIÓN CRÍTICA DE APACHE:
# Habilita el módulo de reescritura (mod_rewrite)
RUN a2enmod rewrite

# Copia el contenido del proyecto
COPY . /var/www/html

# 4. CONFIGURACIÓN CRÍTICA DE APACHE:
# Reemplaza el archivo de configuración por defecto con nuestro archivo personalizado.
# ¡Este paso requiere que el archivo 000-default.conf exista en la raíz de tu proyecto!
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Define el directorio de trabajo
WORKDIR /var/www/html

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Correcciones finales (permissions y .env)
# Da permisos a Laravel para escritura
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 🛑 SOLUCIÓN AL ERROR DE BUILD (key:generate necesita el .env)
# Copia .env.example a .env para que key:generate pueda ejecutarse
RUN cp .env.example .env 

# Genera clave de aplicación y cachea la configuración
RUN php artisan key:generate && php artisan config:cache

# Expone el puerto 80 (Apache)
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]