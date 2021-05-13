up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear docker-pull docker-build docker-up plugin-init

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

plugin-init: plugin-composer-install plugin-wait-db plugin-migrations

plugin-composer-install:
	docker-compose run --rm plugin-php-cli composer install

plugin-wait-db:
	until docker-compose exec -T db pg_isready --timeout=0 --dbname=test_db ; do sleep 1 ; done

plugin-migrations:
	docker-compose run  --rm plugin-php-cli php bin/console d:m:m --no-interaction
