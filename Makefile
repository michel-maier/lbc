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
down:
	docker-compose down

init: up db-drop db-create db-migration-migrate db-fixtures

#Symfony/Composer
install:
	docker-compose run --rm --user="${UID}:${GID}" php composer install

#Doctrine
db-create: up
	@docker-compose run --rm php bin/console doctrine:database:create --if-not-exists
db-drop: up
	@docker-compose run --rm php bin/console doctrine:database:drop --force --if-exists
db-schema-drop: up
	@docker-compose run --rm php bin/console doctrine:schema:drop --full-database --no-interaction
db-migration-diff: up
	@docker-compose run --rm php bin/console doctrine:migration:diff
db-migration-migrate: up
	@docker-compose run --rm php bin/console doctrine:migrations:migrate --no-interaction
db-migration-generate: up
	@docker-compose run --rm --user="${UID}:${GID}" php bin/console doctrine:migrations:generate
db-fixtures: up
	@docker-compose run --rm --user="${UID}:${GID}" php bin/console doctrine:fixtures:load --append --no-interaction
db-initialize: up db-drop db-create db-migration-migrate

#Local dev server
server-dev: up
	docker-compose run --rm --user="${UID}:${GID}" --publish 127.0.0.1:${DEV_SERVER_PORT}:${DEV_SERVER_PORT} php -S 0.0.0.0:${DEV_SERVER_PORT} -t public
server-prod:
	docker-compose run --rm --user="${UID}:${GID}" -e APP_ENV=prod --publish 127.0.0.1:${DEV_SERVER_PORT}:${DEV_SERVER_PORT} php -S 0.0.0.0:${DEV_SERVER_PORT} -t public


#Test
test-core:
	@docker-compose run --rm php simple-phpunit tests/Core
test-func: up
	@docker-compose run --rm -e INIT_DB=1 --user="${UID}:${GID}" php simple-phpunit tests/Functional

#Shell
sh:
	docker-compose run --rm --user="${UID}:${GID}" php /bin/sh