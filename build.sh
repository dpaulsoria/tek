#!/usr/bin/env bash
set -e

# Instala dependencias de PHP
composer install --no-dev --optimize-autoloader
php artisan ziggy:generate resources/js/ziggy.js

# Instala JS y genera assets
npm ci
npm run build
