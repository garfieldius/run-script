#!/usr/bin/env bash

COMBOS='8.1-12.4
8.2-12.4'

C_HOME=$(composer global config home -q)
C_CACHE=$(composer global config cache-dir -q)

set -e -u

for COMBO in $(echo ${COMBOS}); do
    PHP=${COMBO%-*}
    TYPO3=${COMBO#*-}

    docker run --rm -it -u $(id -u):$(id -g) \
        -v "${C_CACHE}:/var/cache/composer" -e COMPOSER_CACHE_DIR=/var/cache/composer \
        -v "${C_HOME}:/var/lib/composer" -e COMPOSER_HOME=/var/lib/composer \
        -v $(pwd):/opt/build -w /opt/build \
        garfieldius/typo3-ci:php${PHP}-node14 \
        /opt/build/test-one.sh ${PHP} ${TYPO3}
done
