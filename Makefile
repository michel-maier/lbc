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
logs:
	docker-compose logs

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
server-prod: up
	docker-compose run --rm --user="${UID}:${GID}" -e APP_ENV=prod --publish 127.0.0.1:${DEV_SERVER_PORT}:${DEV_SERVER_PORT} php -S 0.0.0.0:${DEV_SERVER_PORT} -t public


#Test
test: test-core test-algorithm test-func
test-core:
	@docker-compose run --rm --user="${UID}:${GID}" php simple-phpunit tests/Core
test-core-coverage:
	 @docker-compose run --rm --user="${UID}:${GID}" php php -dxdebug.mode=coverage /.composer/vendor/bin/simple-phpunit tests/Core --coverage-html ./reports
test-algorithm:
	@docker-compose run --rm --user="${UID}:${GID}" php simple-phpunit tests/Unit
test-func: up
	@docker-compose run --rm -e INIT_DB=1 --user="${UID}:${GID}" php simple-phpunit tests/Functional

phpcs:
	@docker-compose run --rm --user="${UID}:${GID}" php php-cs-fixer fix --config=.php-cs-fixer.dist.php --dry-run --verbose || return 0
phpcs-fix:
	@docker-compose run --rm --user="${UID}:${GID}" php php-cs-fixer fix --config=.php-cs-fixer.dist.php --verbose
phpstan:
	@docker-compose run --rm --user="${UID}:${GID}" php phpstan analyse -l 0 src tests || return 0

#Shell
sh:
	docker-compose run --rm --user="${UID}:${GID}" php /bin/sh