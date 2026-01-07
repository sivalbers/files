#!/bin/bash

set -euo pipefail

echo "🔄 Laravel Reset..."

# Composer ruhig & optimiert
composer install --no-dev --optimize-autoloader --no-interaction --quiet

# Laravel Caches löschen
php artisan cache:clear > /dev/null
php artisan optimize:clear > /dev/null
php artisan config:clear > /dev/null
php artisan route:clear > /dev/null
php artisan view:clear > /dev/null
php artisan event:clear > /dev/null

# Log-Datei leeren
truncate -s 0 storage/logs/laravel.log || true

# Caches neu aufbauen
php artisan config:cache > /dev/null
php artisan route:cache > /dev/null
php artisan view:cache > /dev/null
php artisan optimize --quiet

echo "✅ Laravel-Cache wurde neu erstellt."
