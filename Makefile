init: docker-down-clear docker-pull docker-build docker-up
up: docker-up
down: docker-down
build: docker-build
restart: down up
lint: api-lint

api-lint:
	docker-compose run --rm api-php composer lint
	docker-compose run --rm api-php composer cs-check

docker-up:
	docker-compose up -d
docker-down:
	docker-compose down --remove-orphans
docker-down-clear:
	docker-compose down -v --remove-orphans
docker-build:
	docker-compose build
docker-pull:
	docker-compose pull