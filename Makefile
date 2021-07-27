DEV_SERVER_PORT = 8000

UID = $(shell id -u)
GID = $(shell id -g)

build:
	docker-compose build --no-cache
up:
	docker-compose up -d
stop:
	docker-compose stop

install:
	docker-compose run --rm --user="${UID}:${GID}" php composer install
sh:
	docker-compose run --rm --user="${UID}:${GID}" php /bin/sh
server-start:
	docker-compose run --rm --user="${UID}:${GID}" --publish 127.0.0.1:${DEV_SERVER_PORT}:${DEV_SERVER_PORT} php -S 0.0.0.0:${DEV_SERVER_PORT} -t public
server-stop:
	docker-compose run --rm --user="${UID}:${GID}" --publish 127.0.0.1:${DEV_SERVER_PORT}:${DEV_SERVER_PORT} php -S 0.0.0.0:${DEV_SERVER_PORT} -t public

test:
	docker-compose run --rm php simple-phpunit