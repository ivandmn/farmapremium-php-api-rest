#!/bin/sh
set -e

log() {
    echo "[entrypoint] $1"
}

if [ "$1" = 'frankenphp' ]; then
    if [ "$APP_ENV" != 'prod' ] && [ ! -f vendor/autoload_runtime.php ]; then
        log "vendor/ no encontrado, ejecutando composer install..."
        composer install --prefer-dist --no-progress --no-interaction
    fi

    if [ "${RUN_MIGRATIONS:-false}" = 'true' ]; then
        log "Esperando a la base de datos..."
        ATTEMPTS_LEFT=${DB_WAIT_ATTEMPTS:-30}
        until bin/console dbal:run-sql -q "SELECT 1" >/dev/null 2>&1; do
            ATTEMPTS_LEFT=$((ATTEMPTS_LEFT - 1))
            if [ "$ATTEMPTS_LEFT" -le 0 ]; then
                log "La base de datos no responde:"
                bin/console dbal:run-sql "SELECT 1"
                exit 1
            fi
            sleep 2
        done

        log "Ejecutando migraciones..."
        bin/console doctrine:migrations:migrate --no-interaction --all-or-nothing --allow-no-migration
    fi
fi

exec docker-php-entrypoint "$@"
