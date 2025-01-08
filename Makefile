init: docker-down-clear docker-pull docker-build docker-up api-init
up: docker-up
down: docker-down
build: docker-build
bash: docker-bash
api-init: composer-install wait-db

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-build:
	docker compose build

docker-pull:
	docker compose pull

docker-bash:
	docker compose exec -it php-fpm bash

composer-install:
	docker compose run --rm php-fpm composer install --dev

wait-db:
	docker compose run --rm php-fpm wait-for-it mysql:3306 -t 30

validate:
	docker compose run --rm php-fpm composer run validate
