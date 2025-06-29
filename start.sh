#!/usr/bin/env bash
set -e

# Inyectamos el certificado
mkdir -p storage/certs
echo "$MYSQL_ATTR_SSL_CA_B64" | base64 -d > storage/certs/ca.pem

# Migraciones y caches
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Finalmente arrancamos Laravel
php artisan serve --host=0.0.0.0 --port=10000
