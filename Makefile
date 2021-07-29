DEV_SERVER_PORT = 8000

UID = $(shell id -u)
GID = $(shell id -g)

#Docker
build:
	docker-compose build --no-cache
up:
	docker-compose up -d
stop:
	docker-compose stop

#Symfony/Composer
install:
	docker-compose run --rm --user="${UID}:${GID}" php composer install

#Doctrine
db-create: up
	@docker-compose run --rm --user="${UID}:${GID}" php bin/console doctrine:database:create --if-not-exists
db-migration-diff: up
	@docker-compose run --rm --user="${UID}:${GID}" php bin/console doctrine:migration:diff
db-migration-migrate: up
	@docker-compose run --rm --user="${UID}:${GID}" php bin/console doctrine:migrations:migrate --no-interaction
db-migration-generate: up
	@docker-compose run --rm --user="${UID}:${GID}" php bin/console doctrine:migrations:generate

#Local server
server-start: up
	docker-compose run --rm --user="${UID}:${GID}" --publish 127.0.0.1:${DEV_SERVER_PORT}:${DEV_SERVER_PORT} php -S 0.0.0.0:${DEV_SERVER_PORT} -t public

#Test
test:
	@docker-compose run --rm php simple-phpunit

#Shell
sh:
	docker-compose run --rm --user="${UID}:${GID}" php /bin/sh