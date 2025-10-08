#!/bin/bash
set -e

log() {
    echo -e "\033[0;32m[ENTRYPOINT]\033[0m $1"
}

error() {
    echo -e "\033[0;31m[ENTRYPOINT ERROR]\033[0m $1"
    exit 1
}

setup_permissions() {
    log "Setting up directories and permissions..."
    mkdir -p var/log var/cache var/sessions

    if [ "$APP_ENV" = "dev" ]; then
        chmod -R 0777 var/ || true
        log "Development permissions applied"
    else
        chown -R www-data:www-data /var/www/html
        chmod -R 755 var/ || true
        log "Production permissions applied"
    fi
}

log "Starting entrypoint for environment: ${APP_ENV:-dev}"

setup_permissions

log "Initialization completed. Starting: $*"
exec "$@"
