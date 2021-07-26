export UID = $(shell id -u)
export GID = $(shell id -g)

build:
	docker-compose build
up:
	docker-compose up
stop:
	docker-compose stop

install:
	docker-compose run --rm --user="$(UID):$(GID)" php composer install
sh:
	docker-compose run --rm --user="$(UID):$(GID)" php /bin/sh