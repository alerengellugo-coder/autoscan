#!/bin/sh
set -e

echo "Starting AutoScan deployment..."

# Create supervisor log directory (needed at runtime)
mkdir -p /var/log/supervisor /var/run

# Clear any stale config cache from build
php artisan config:clear 2>/dev/null || true

# Generate application key if not set (MUST be before any encrypted operations)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "SomeRandomStringSomeRandomString" ] || [ "$APP_KEY" = "null" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Wait for database
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

# Drop all existing tables and views (clean slate for Neon)
echo "Wiping existing database tables..."
php artisan db:wipe --force --no-interaction 2>/dev/null || true

# Small delay to let Neon pooler release connections after wipe
sleep 2

# Run migrations fresh
echo "Running database migrations..."
php artisan migrate --force --no-interaction

# Always run seeders
echo "Running seeders..."
php artisan db:seed --force --no-interaction 2>/dev/null || echo "WARNING: Seeder failed, continuing..."

# Clear and rebuild caches (view:cache skipped - Inertia.js doesn't use Blade views)
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache 2>/dev/null || echo "NOTE: view:cache skipped (no Blade views)"
php artisan event:cache 2>/dev/null || true

# Create storage link
php artisan storage:link --force 2>/dev/null || true

# Start supervisord (nginx + php-fpm)
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
