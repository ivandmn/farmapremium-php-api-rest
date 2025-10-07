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
        chmod -R 755 var/
        chmod -R 775 var/cache var/log var/sessions
        log "Production permissions applied"
    fi
}

run_migrations() {
    if [ "$APP_ENV" != "dev" ] && [ -d "migrations" ]; then
        if [ "$(find migrations -name '*.php' 2>/dev/null | wc -l)" -gt 0 ]; then
            log "Running database migrations..."
            php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration || true
        fi
    fi
}

health_check() {
    [ -f "bin/console" ] || error "Symfony console not found"
    [ -f "vendor/autoload.php" ] || error "Composer dependencies missing. Run: make build"
    php-fpm -t || error "PHP-FPM configuration invalid"
    log "Health checks passed"
}

log "Starting entrypoint for environment: ${APP_ENV:-dev}"

setup_permissions
run_migrations
health_check

log "Initialization completed. Starting: $*"
exec "$@"
