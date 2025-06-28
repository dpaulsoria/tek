#!/usr/bin/env bash
set -e

# Instala dependencias de PHP
composer install --no-dev --optimize-autoloader

# Instala JS y genera assets
npm ci
npm run build
