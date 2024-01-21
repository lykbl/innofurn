sail:=bin/sail
image-name:=laravel.test

up:
	$(sail) build
	$(sail) up -d
	make logs

down:
	$(sail) down

reup:
	make down
	make up

migrate-create:
	$(sail) artisan make:migration name=$(name)

migrate-up:
	$(sail) artisan migrate

migrate-down:
	$(sail) artisan migrate:rollback --step=1

post-deploy:
	$(sail) artisan clear-compiled && chmod -R 777 public/

config-clear:
	$(sail) config:clear

colorize=printf $(terminalCommand)$(asciiGreen)"%s"$(terminalCommand)$(asciiDefault) "$1"
logs:
	tail -n 60 -F $(PWD)/storage/logs/*.log | awk '/INFO/ { print "\033[92m" $$0 "\033[39m" } /DEBUG/ {print "\033[94m" $$0 "\033[39m"} /ERROR/{print "\033[91m" $$0 "\033[39m"} /WARNING/{print "\033[93m" $$0 "\033[39m"} /CRITICAL/{print "\033[95m" $$0 "\033[39m"} !/INFO|DEBUG|ERROR|WARNING|CRITICAL/{print "\033[96m" $$0 "\033[39m"}'

cs-fix:
	docker exec -it $(image-name) ./bin/php-cs-fixer fix

#artisan-run:
#	docker exec -it $(image-name) php artisan

seed:
	$(sail) artisan seeders:run

shell:
	docker exec -it "$(image-name)" /bin/bash

reload:
	docker exec -it $(image-name) /bin/bash -c 'kill -USR2 $$(ps -ef | grep "php-fpm: master" | grep -v grep | awk "{print \$$2}" | head -1)'

graphql-generate:
	$(sail) artisan lighthouse:ide-helper

patch-generate:
	docker exec -it $(image-name) ./bin/vendor-patches generate

composer-install:
	docker exec -it $(image-name) composer install
