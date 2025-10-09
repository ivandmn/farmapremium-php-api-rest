# =======================================
# Base
# =======================================
FROM php:8.3-fpm AS base

WORKDIR /var/www/html

ARG APP_ENV=${APP_ENV}
ARG USER_ID=1000
ARG GROUP_ID=1000
ARG USER_NAME=appuser

ENV APP_ENV=${APP_ENV}
ENV TZ=Europe/Madrid
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zlib1g-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql  \
    intl \
    zip \
    opcache \
    mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# =======================================
# Development
# =======================================
FROM base AS dev

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    wget \
    curl \
    nano \
    vim \
    make \
    net-tools \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
	&& rm -rf /var/lib/apt/lists/*

RUN groupadd -g ${GROUP_ID} ${USER_NAME} \
    && useradd -u ${USER_ID} -g ${USER_NAME} -m -s /bin/bash ${USER_NAME}

RUN mkdir -p var/log var/cache var/sessions \
    && chmod -R 0777 var/ \
    && chown -R ${USER_NAME}:${USER_NAME} /var/www/html

USER ${USER_NAME}

# Xdebug Port
EXPOSE 9003

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]

# =======================================
# Production
# =======================================
FROM base AS prod

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress \
	&& composer dump-autoload --optimize --classmap-authoritative \
	&& rm -rf /root/.composer/cache

RUN chown -R www-data:www-data /var/www/html

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
