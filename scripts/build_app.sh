#!/usr/bin/env bash

composer install --optimize-autoloader --prefer-dist

php bin/console doctrine:migrations:migrate --env=prod --no-interaction
rm -R ./var/cache/*
php bin/console cache:warmup --env=prod

chown -R www-data:www-data .
