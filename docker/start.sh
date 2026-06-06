#!/bin/sh
set -e

echo "Starting AutoScan deployment..."

# Generate application key if not set (MUST be before migrations & config:cache)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "SomeRandomStringSomeRandomString" ] || [ "$APP_KEY" = "null" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Wait for database (works with Neon external DB)
if [ -n "$DB_HOST" ]; then
    echo "Waiting for PostgreSQL at $DB_HOST:${DB_PORT:-5432}..."
    for i in $(seq 1 30); do
        if php artisan tinker --execute="try { \Illuminate\Support\Facades\DB::connection()->getPdo(); echo 'OK'; } catch(Exception \$e) { echo 'WAITING'; }" 2>/dev/null | grep -q "OK"; then
            echo "PostgreSQL is ready!"
            break
        fi
        if [ "$i" = "30" ]; then
            echo "WARNING: Could not verify DB connection, proceeding anyway..."
        else
            echo "PostgreSQL not ready (attempt $i/30), waiting 2s..."
            sleep 2
        fi
    done
fi

# Run migrations (fresh to handle re-deploys cleanly)
echo "Running database migrations..."
php artisan migrate:fresh --force --no-interaction

# Always run seeders after fresh migration
echo "Running seeders..."
php artisan db:seed --force --no-interaction

# Clear and rebuild caches
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Create storage link
php artisan storage:link --force 2>/dev/null || true

# Start supervisord (nginx + php-fpm)
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
