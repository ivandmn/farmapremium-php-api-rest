export USER_ID := $(shell id -u)
export GROUP_ID := $(shell id -g)
export USER ?= $(shell whoami)

NEED_ENV := up down build logs bash restart ps stop start prune composer-install composer-update test

$(NEED_ENV): check-env

ENV ?= dev
COMPOSE_FILES ?=


# docker-compose.yml = prod (lo que despliega Dokploy); el override añade dev encima
ifeq ($(COMPOSE_FILES),)
	COMPOSE_FILES = -f docker-compose.yml
	ifeq ($(ENV),dev)
		COMPOSE_FILES += -f docker-compose.override.yml
	endif
endif


ifeq ($(ENV),dev)
	ENV_FILES = --env-file .env --env-file .env.local --env-file .env.dev --env-file .env.dev.local
else ifeq ($(ENV),prod)
	ENV_FILES = --env-file .env --env-file .env.local --env-file .env.prod --env-file .env.prod.local
endif

ENV_FILES_FILTERED = $(foreach file,$(subst --env-file ,,$(ENV_FILES)),$(if $(wildcard $(file)),--env-file $(file)))

up:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) up -d

down:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) down

build:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) build --no-cache

logs:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) logs -f

bash:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) exec app bash

restart: down up

ps:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) ps

stop:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) stop

start:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) start

prune:
	@echo "This will remove all unused Docker resources (containers, networks, images, volumes)"
	@read -p "Are you sure? [y/N]: " confirm && [ "$$confirm" = "y" ] || [ "$$confirm" = "Y" ]
	docker stop $$(docker ps -aq) 2>/dev/null || true
	docker rm -f $$(docker ps -aq) 2>/dev/null || true
	docker network rm $$(docker network ls -q) 2>/dev/null || true
	docker volume rm $$(docker volume ls -q) 2>/dev/null || true
	docker rmi -f $$(docker images -q) 2>/dev/null || true
	docker builder prune -af
	docker system prune -af --volumes

composer-install:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) exec app composer install

composer-update:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) exec app composer update

test:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) exec app bash -lc 'APP_ENV=test bin/console doctrine:database:create --if-not-exists'
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) exec app bash -lc 'APP_ENV=test bin/console doctrine:migrations:migrate -n'
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) exec -e XDEBUG_MODE=coverage app bash -c "APP_ENV=test ./bin/phpunit"

check-env:
	@if [ ! -f .env ]; then \
		echo "No .env file found. Creating one from .env.example..."; \
		cp .env.example .env; \
	fi

show-config:
	@echo "Environment: $(ENV)"
	@echo "Compose files: $(COMPOSE_FILES)"
	@echo "Env files: $(ENV_FILES_FILTERED)"
