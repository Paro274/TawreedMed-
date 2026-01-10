#!/bin/bash
set -e

echo "Starting deployment setup..."

# 1. Fix Permissions (Verbose)
echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 2. Check for APP_KEY
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is missing! Generating a new one..."
    php artisan key:generate --force
fi

# 3. Cache Everything (Critical for production)
echo "Caching configuration..."
php artisan config:cache
echo "Caching routes..."
php artisan route:cache
echo "Caching views..."
php artisan view:cache
echo "Caching events..."
php artisan event:cache

# 4. Storage Link
echo "Creating storage link..."
php artisan storage:link || true

# 5. Run Migrations (Safe force)
# Uncomment the next line if you want to auto-migrate on deploy
# echo "Running migrations..."
# php artisan migrate --force

echo "Deployment setup complete. Starting Apache..."

# 6. Execute CMD
exec "$@"
