ENV ?= dev

up:
	docker compose -f docker-compose.yml -f docker/$(ENV)/docker-compose.$(ENV).yml up -d

down:
	docker compose -f docker-compose.yml -f docker/$(ENV)/docker-compose.$(ENV).yml down

build:
	docker compose -f docker-compose.yml -f docker/$(ENV)/docker-compose.$(ENV).yml build --no-cache
logs:
	docker compose -f docker-compose.yml -f docker/$(ENV)/docker-compose.$(ENV).yml logs -f

bash:
	docker compose exec app bash