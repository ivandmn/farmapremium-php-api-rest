# =======================================
# Base
# =======================================
FROM dunglas/frankenphp:1.13.0-php8.5 AS base

WORKDIR /web

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
ENV PHP_INI_SCAN_DIR=":$PHP_INI_DIR/app.conf.d"

RUN apt-get update && apt-get install -y --no-install-recommends \
    acl \
    curl \
    file \
    gettext-base \
    git \
    libcap2-bin \
    && setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp \
    && rm -rf /var/lib/apt/lists/*

# OPcache viene integrado en PHP 8.5, no hace falta instalarlo.
RUN install-php-extensions \
    @composer \
    amqp \
    apcu \
    exif \
    gd \
    intl \
    pcntl \
    pdo_pgsql \
    redis \
    zip

COPY etc/caddy/Caddyfile /etc/frankenphp/Caddyfile
COPY --chmod=755 etc/app/docker-entrypoint.sh /usr/local/bin/docker-entrypoint

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1

ENTRYPOINT ["docker-entrypoint"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]

# =======================================
# Development
# =======================================
FROM base AS dev

ARG USER
ARG USER_ID
ARG GROUP_ID
ARG USER_NAME
ARG USER_EMAIL

ENV APP_ENV=dev
ENV XDEBUG_MODE=off
ENV FRANKENPHP_WORKER_CONFIG=watch

RUN apt-get update && apt-get install -y --no-install-recommends \
    bash \
    make \
    nano \
    openssh-client \
    unzip \
    vim \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions xdebug \
    && cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY etc/app/php.dev.ini $PHP_INI_DIR/app.conf.d/10-app.ini

# Usuario sin root con el mismo UID que tu usuario de WSL (evita problemas de permisos)
RUN (getent group ${GROUP_ID} || groupadd -g ${GROUP_ID} ${USER}) \
    && useradd -m -s /bin/bash -u ${USER_ID} -g ${GROUP_ID} ${USER} \
    && mkdir -p /home/${USER}/.ssh \
    && chown -R ${USER_ID}:${GROUP_ID} /home/${USER} /web /config/caddy /data/caddy

USER ${USER}

RUN git config --global --add safe.directory /web \
    && if [ -n "${USER_NAME}" ]; then git config --global user.name "${USER_NAME}"; fi \
    && if [ -n "${USER_EMAIL}" ]; then git config --global user.email "${USER_EMAIL}"; fi

# =======================================
# Production
# =======================================
FROM base AS prod

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV FRANKENPHP_WORKER_CONFIG="num 4"

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY etc/app/php.prod.ini $PHP_INI_DIR/app.conf.d/10-app.ini

COPY composer.* symfony.* ./
RUN composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

COPY . ./

RUN mkdir -p var/cache var/log \
    && echo 'APP_ENV=prod' > .env \
    && composer dump-autoload --classmap-authoritative --no-dev \
    && composer dump-env prod \
    && APP_SECRET=build DEFAULT_URI=http://localhost DATABASE_URL="postgresql://build:build@localhost:5432/build?serverVersion=18&charset=utf8" \
        composer run-script --no-dev post-install-cmd \
    && chmod +x bin/console \
    && chown -R www-data:www-data var /config/caddy /data/caddy

USER www-data
