#!/usr/bin/env bash

cd /var/www/migrations || exit

composer install --optimize-autoloader --prefer-dist
bin/doctrine-migrations migrations:migrate -vvv --no-interaction
chown -R www-data:www-data .

cd /var/www/html || exit

composer install --optimize-autoloader --prefer-dist

php bin/console doctrine:migrations:migrate --env=prod -vvv --no-interaction
rm -R ./var/cache/*
php bin/console cache:warmup --env=prod

chown -R www-data:www-data .
