#!/bin/bash
set -e

# Default PORT to 80 if not set (Render provides PORT automatically)
export PORT=${PORT:-80}

echo "--> Configuring Nginx for PORT ${PORT}..."
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

# Ensure storage and database directories exist
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/database

# Ensure SQLite file exists if using SQLite
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "--> Initializing database.sqlite..."
    touch /var/www/html/database/database.sqlite
fi

# Set directory permissions for www-data
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod 664 /var/www/html/database/database.sqlite 2>/dev/null || true

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

# Run database migrations and seeders automatically
if [ "$RUN_MIGRATIONS" != "false" ]; then
    echo "--> Running database migrations..."
    php artisan migrate --force || true
    
    if [ "$RUN_SEEDER" != "false" ]; then
        echo "--> Running database seeder..."
        php artisan db:seed --force || true
    fi
fi

echo "--> Starting Supervisor (Nginx + PHP-FPM)..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
