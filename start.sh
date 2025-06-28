#!/usr/bin/env bash
set -e

mkdir -p storage/certs
echo "$MYSQL_ATTR_SSL_CA_B64" | base64 -d > storage/certs/ca.pem

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php-fpm
