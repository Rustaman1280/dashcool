#!/bin/bash
set -e

# Default PORT to 80 if not set (Render provides PORT automatically)
export PORT=${PORT:-80}

echo "--> Configuring Nginx for PORT ${PORT}..."
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

# Ensure storage directories exist and have proper permissions
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage link if not exists
if [ ! -L /var/www/html/public/storage ]; then
    echo "--> Creating storage symlink..."
    php artisan storage:link || true
fi

# Run caching in production
echo "--> Optimizing Laravel caches..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run database migrations if configured
if [ "$RUN_MIGRATIONS" = "true" ] || [ "$AUTO_MIGRATE" = "true" ]; then
    echo "--> Running database migrations..."
    php artisan migrate --force || true
    
    if [ "$RUN_SEEDER" = "true" ]; then
        echo "--> Running database seeder..."
        php artisan db:seed --force || true
    fi
fi

echo "--> Starting Supervisor (Nginx + PHP-FPM)..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
