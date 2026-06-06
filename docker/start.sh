#!/bin/sh
set -e

echo "Starting AutoScan deployment..."

# Wait for database (works with Neon external DB)
if [ -n "$DB_HOST" ]; then
    echo "Waiting for PostgreSQL at $DB_HOST:$DB_PORT..."
    for i in $(seq 1 30); do
        if php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'OK'; } catch(Exception \$e) { echo 'WAITING'; }" 2>/dev/null | grep -q "OK"; then
            echo "PostgreSQL is ready!"
            break
        fi
        echo "PostgreSQL not ready (attempt $i/30), waiting 2s..."
        sleep 2
    done
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force --no-interaction

# Run seeders if the users table is empty
TABLE_COUNT=$(php artisan tinker --execute="echo DB::table('users')->count();" 2>/dev/null | grep -oE '[0-9]+' || echo "0")
if [ "$TABLE_COUNT" = "0" ]; then
    echo "Running seeders (no users found)..."
    php artisan db:seed --force --no-interaction
fi

# Clear caches
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "SomeRandomStringSomeRandomString" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Create storage link
php artisan storage:link --force 2>/dev/null || true

# Start supervisord (nginx + php-fpm)
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
