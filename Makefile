# Makefile
PORT ?= 8000
start:
	PHP_CLI_SERVER_WORKERS=5 php -S 0.0.0.0:$(PORT) -t public

install:
	composer install

validate:
	composer validate --strict

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app

lint-fix:
	@composer exec --verbose phpcbf -- --standard=PSR12 app
