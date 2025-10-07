ENV ?= dev
COMPOSE_FILES ?=


ifeq ($(COMPOSE_FILES),)
	COMPOSE_FILES = -f docker-compose.yml
	ifeq ($(ENV),dev)
		COMPOSE_FILES += -f docker-compose.override.yml
	else ifeq ($(ENV),staging)
		COMPOSE_FILES += -f docker-compose.staging.yml
	else ifeq ($(ENV),prod)
		COMPOSE_FILES += -f docker-compose.prod.yml
	endif
endif


ifeq ($(ENV),dev)
	ENV_FILES = --env-file .env --env-file .env.local --env-file .env.dev --env-file .env.dev.local
else ifeq ($(ENV),staging)
	ENV_FILES = --env-file .env --env-file .env.local --env-file .env.staging --env-file .env.staging.local
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
	docker system prune -f
	docker volume prune -f
	docker network prune -f

composer-install:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) exec app composer install

composer-update:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) exec app composer update

test:
	docker compose $(ENV_FILES_FILTERED) $(COMPOSE_FILES) exec app APP_ENV=test ./bin/phpunit

show-config:
	@echo "Environment: $(ENV)"
	@echo "Compose files: $(COMPOSE_FILES)"
	@echo "Env files: $(ENV_FILES_FILTERED)"