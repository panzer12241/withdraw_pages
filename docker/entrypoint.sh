#!/bin/sh
set -e

# Create required Laravel storage directories (no brace expansion in /bin/sh)
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Sync public folder (including built assets) to shared volume
cp -r /var/www/html/public/. /var/www/html/public_volume/

# Cache Laravel config/routes
php artisan config:cache
php artisan route:cache

exec "$@"
