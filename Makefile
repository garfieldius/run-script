
.PHONY: build
build: vendor/autoload.php

.PHONY: style-check
style-check: vendor/autoload.php .php-cs-fixer.php
	@vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php -vvv --diff --dry-run

.PHONY: style-fix
style-fix: vendor/autoload.php .php-cs-fixer.php
	@vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php -vvv --diff

.PHONY: test
test: vendor/autoload.php phpunit.xml
	@vendor/bin/phpunit -c phpunit.xml

.PHONY: coverage
coverage: vendor/autoload.php phpunit.xml
	@rm -rf report && mkdir report && XDEBUG_MODE=coverage vendor/bin/phpunit -c phpunit.xml --coverage-html report/

.PHONY: clean
clean:
	@rm -rf report vendor .php-cs-fixer.cache .php-cs-fixer.php .phpunit.result.cache phpunit.xml

phpunit.xml: phpunit.xml.dist
	@cp phpunit.xml.dist phpunit.xml && touch phpunit.xml

.php-cs-fixer.php: .php-cs-fixer.php.dist
	@cp .php-cs-fixer.php.dist .php-cs-fixer.php && touch .php-cs-fixer.php

vendor/autoload.php: composer.json composer.lock
	@composer install --no-plugins --no-scripts && touch vendor/autoload.php
