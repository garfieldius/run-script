
.PHONY: build
build: vendor/autoload.php

.PHONY: style-check
style-check: vendor/autoload.php .php_cs
	@vendor/bin/php-cs-fixer fix --config=.php_cs -vvv --diff --diff-format=udiff --dry-run

.PHONY: style-fix
style-fix: vendor/autoload.php .php_cs
	@vendor/bin/php-cs-fixer fix --config=.php_cs -vvv --diff --diff-format=udiff

.PHONY: test
test: vendor/autoload.php phpunit.xml
	@vendor/bin/phpunit -c phpunit.xml

.PHONY: coverage
coverage: vendor/autoload.php phpunit.xml
	@rm -rf report && mkdir report && XDEBUG_MODE=coverage vendor/bin/phpunit -c phpunit.xml --coverage-html report/

phpunit.xml: phpunit.xml.dist
	@cp phpunit.xml.dist phpunit.xml && touch phpunit.xml

.php_cs: .php_cs.dist
	@cp .php_cs.dist .php_cs && touch .php_cs

vendor/autoload.php: composer.json composer.lock
	@composer install && touch vendor/autoload.php
