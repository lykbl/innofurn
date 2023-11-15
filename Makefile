sail:=bin/sail
artisan:=$(sail) artisan
rollback-step:=1
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
	$(artisan) make:migration name=$(name)

post-deploy:
	vendor/bin/sail artisan clear-compiled && chmod -R 777 public/

migrate-up:
	$(artisan) migrate

migrate-down:
	$(artisan) migrate:rollback --step=$(rollback-step)

config-clear:
	$(artisan) config:clear

colorize=printf $(terminalCommand)$(asciiGreen)"%s"$(terminalCommand)$(asciiDefault) "$1"
logs:
	tail -n 60 -F $(PWD)/storage/logs/*.log | awk '/INFO/ { print "\033[92m" $$0 "\033[39m" } /DEBUG/ {print "\033[94m" $$0 "\033[39m"} /ERROR/{print "\033[91m" $$0 "\033[39m"} /WARNING/{print "\033[93m" $$0 "\033[39m"} /CRITICAL/{print "\033[95m" $$0 "\033[39m"} !/INFO|DEBUG|ERROR|WARNING|CRITICAL/{print "\033[96m" $$0 "\033[39m"}'

cs-fix:
	./bin/php-cs-fixer fix

artisan:
	$(artisan) $(command)

seed:
	$(artisan) db:seed --class=$(name)

shell:
	docker exec -it "$(image-name)" /bin/bash

reload:
	docker exec -it $(image-name) /bin/bash -c 'kill -USR2 $$(ps -ef | grep "php-fpm: master" | grep -v grep | awk "{print \$$2}" | head -1)'

graphql-dev:
	$(artisan) lighthouse:ide-helper
