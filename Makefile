start:
	docker-compose up -d --remove-orphans

stop:
	docker-compose stop

build:
	composer self-update
	composer install
init:
	php bin/console doctrine:database:drop --force
	php bin/console doctrine:database:create
	php bin/console doctrine:migration:migrate --no-interaction
	php bin/console doctrine:fixture:load --no-interaction