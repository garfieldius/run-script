#!/usr/bin/env bash

PHP_VERSION=${1}
TYPO3_VERSION=${2}

cd $(cd $(dirname $0) && pwd)

set -e -u

jq 'del(.require)' composer.json | jq 'del(.["require-dev"])' > c.json
rm -rf composer.lock vendor public composer.json
mv c.json composer.json
composer require --ansi -n --no-progress --no-plugins --no-scripts typo3/cms-backend:^${TYPO3_VERSION} typo3/cms-core:^${TYPO3_VERSION} typo3/cms-extbase:^${TYPO3_VERSION} typo3/cms-fluid:^${TYPO3_VERSION}
composer require --dev --ansi -n --no-progress --no-plugins --no-scripts phpunit/phpunit mikey179/vfsstream
php vendor/bin/phpunit -c phpunit.xml.dist

make clean
git checkout -- composer.json composer.lock
