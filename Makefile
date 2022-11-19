start:
	docker compose up -d --remove-orphans
	symfony server:start -d

stop:
	docker compose stop
	symfony server:stop

build:
	composer self-update
	composer install
init:
	symfony console doctrine:database:drop --force
	symfony console doctrine:database:create
	symfony console doctrine:migration:migrate --no-interaction
	symfony console doctrine:fixture:load --no-interaction