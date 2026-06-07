#!/bin/sh

echo "=== AutoScan Deployment Starting ==="

# Create required runtime directories
mkdir -p /var/log/supervisor /var/run /var/log/nginx
chmod 777 /var/run

# Remove old Alpine nginx configs to avoid conflicts with our custom nginx.conf
rm -f /etc/nginx/conf.d/default.conf
rm -f /etc/nginx/http.d/default.conf 2>/dev/null || true

# Set PORT env var (Render sets this automatically)
export PORT=${PORT:-10000}
echo "Using PORT=$PORT"

# Replace port in nginx.conf using sed (safe - only replaces the listen directive)
sed -i "s/listen 10000 default_server/listen $PORT default_server/" /etc/nginx/nginx.conf
echo "Nginx configured to listen on port $PORT"

# Generate application key if needed
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "SomeRandomStringSomeRandomString" ] || [ "$APP_KEY" = "null" ]; then
    echo "Generating application key..."
    php artisan key:generate --force 2>&1 || echo "WARNING: key:generate failed"
fi

# Wait for database
if [ -n "$DB_HOST" ]; then
    echo "Waiting for PostgreSQL at ${DB_HOST}:${DB_PORT:-5432}..."
    for i in $(seq 1 15); do
        if php artisan tinker --execute="try { \Illuminate\Support\Facades\DB::connection()->getPdo(); echo 'OK'; } catch(Exception \$e) { echo 'WAIT'; }" 2>/dev/null | grep -q "OK"; then
            echo "PostgreSQL is ready!"
            break
        fi
        echo "  Waiting... (attempt $i/15)"
        sleep 2
    done
fi

# Drop all tables for clean state
echo "Wiping database..."
php artisan db:wipe --force --no-interaction 2>&1 || echo "  db:wipe skipped or failed"
sleep 1

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction 2>&1 || {
    echo "ERROR: Migrations failed!"
    # Continue anyway - don't block startup
}

# Run seeders
echo "Running seeders..."
php artisan db:seed --force --no-interaction 2>&1 || echo "  Seeder failed, continuing..."

# Optimize
echo "Caching config..."
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan event:cache 2>&1 || true

# Storage link
php artisan storage:link --force 2>/dev/null || true

echo "=== Starting nginx + php-fpm ==="
echo "Nginx will listen on port $PORT"

# Test nginx config before starting
nginx -t 2>&1 || echo "WARNING: nginx config test failed"

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
