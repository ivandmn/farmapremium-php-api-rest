FROM php:8.3-fpm

# 🔹 Dependencias necesarias para PHP y extensiones
RUN apt-get update && apt-get install -y \
    libicu-dev libpq-dev libzip-dev zlib1g-dev libonig-dev \
    && docker-php-ext-install intl pdo pdo_pgsql zip opcache mbstring

# 🔹 Utilidades de sistema (para desarrollo y debugging)
RUN apt-get install -y \
    git unzip zip wget curl nano vim make net-tools \
    && rm -rf /var/lib/apt/lists/*

# 🔹 Instalar Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# 🔹 Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 🔹 Configuración de trabajo
WORKDIR /var/www/html

# 🔹 Permisos
RUN mkdir -p /var/www/html/var/log \
    && chmod -R 777 /var/www/html/var/log \
    && chown -R www-data:www-data /var/www/html

# 🔹 Variables útiles para Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
ENV TZ=Europe/Madrid

EXPOSE 9003

CMD ["php-fpm"]
