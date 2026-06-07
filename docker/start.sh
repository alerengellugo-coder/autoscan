#!/bin/sh

echo "=== AutoScan Deployment Starting ==="

# Create required runtime directories
mkdir -p /var/run /var/log/nginx
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

# Test nginx config
echo "Testing nginx config..."
nginx -t 2>&1
if [ $? -ne 0 ]; then
    echo "FATAL: nginx config test failed!"
    echo "=== nginx.conf contents ==="
    cat /etc/nginx/nginx.conf
    echo "=== end nginx.conf ==="
    # Don't exit - try to continue for debugging
fi

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
}

# Run seeders
echo "Running seeders..."
php artisan db:seed --force --no-interaction 2>&1 || echo "  Seeder failed, continuing..."

# Clear any cached files (don't cache - let Laravel handle in runtime)
echo "Clearing cached files..."
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan event:clear 2>&1 || true
php artisan view:clear 2>&1 || true
php artisan cache:clear 2>&1 || true

# Storage link
php artisan storage:link --force 2>/dev/null || true

echo "=== Starting nginx + php-fpm ==="

# Start PHP-FPM in background
php-fpm -F 2>&1 &
echo "PHP-FPM started (pid $!)"

# Give php-fpm a moment to create the socket
sleep 2

# Verify socket exists
if [ -S /var/run/php-fpm.sock ]; then
    echo "PHP-FPM socket created successfully"
else
    echo "WARNING: PHP-FPM socket not found at /var/run/php-fpm.sock"
    ls -la /var/run/ 2>&1
fi

# Start nginx in foreground (this keeps the container running)
echo "Starting nginx on port $PORT..."
exec nginx -g 'daemon off;'
