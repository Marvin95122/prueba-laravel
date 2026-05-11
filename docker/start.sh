#!/usr/bin/env bash

set -e

mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

chmod -R 775 storage bootstrap/cache || true

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force

php artisan storage:link || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan serve --host=0.0.0.0 --port=${PORT:-10000}