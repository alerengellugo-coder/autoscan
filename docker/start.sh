#!/bin/sh

echo "=== AutoScan Deployment Starting ==="

mkdir -p /var/run /var/log/nginx
chmod 777 /var/run

mkdir -p /var/www/html/storage/framework/{views,cache,sessions}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

rm -f /etc/nginx/conf.d/default.conf
rm -f /etc/nginx/http.d/default.conf 2>/dev/null || true

export PORT=${PORT:-10000}
echo "Using PORT=$PORT"
sed -i "s/listen 10000 default_server/listen $PORT default_server/" /etc/nginx/nginx.conf

nginx -t 2>&1

if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "SomeRandomStringSomeRandomString" ] || [ "$APP_KEY" = "null" ]; then
    echo "Generating application key..."
    php artisan key:generate --force 2>&1 || echo "WARNING: key:generate failed"
fi

# Switch session and cache drivers to database to avoid file system issues
echo "Setting session/cache drivers to database..."
sed -i 's/^SESSION_DRIVER=.*/SESSION_DRIVER=database/' /var/www/html/.env 2>/dev/null || true
sed -i 's/^CACHE_DRIVER=.*/CACHE_DRIVER=database/' /var/www/html/.env 2>/dev/null || true
# Also add these if they don't exist
grep -q "^SESSION_DRIVER=" /var/www/html/.env 2>/dev/null || echo "SESSION_DRIVER=database" >> /var/www/html/.env
grep -q "^CACHE_DRIVER=" /var/www/html/.env 2>/dev/null || echo "CACHE_DRIVER=database" >> /var/www/html/.env
# Clear config so the new .env values take effect
php artisan config:clear 2>&1 || true

echo "Session driver: $(php artisan tinker --execute='echo config(\"session.driver\");' 2>/dev/null)"
echo "Cache driver: $(php artisan tinker --execute='echo config(\"cache.default\");' 2>/dev/null)"

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

echo "Wiping database..."
php artisan db:wipe --force --no-interaction 2>&1 || echo "  db:wipe skipped or failed"
sleep 1

echo "Running migrations..."
php artisan migrate --force --no-interaction 2>&1 || echo "ERROR: Migrations failed!"

echo "Running seeders..."
php artisan db:seed --force --no-interaction 2>&1 || echo "  Seeder failed, continuing..."

echo "Clearing cached files..."
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan event:clear 2>&1 || true
php artisan view:clear 2>&1 || true
php artisan cache:clear 2>&1 || true

mkdir -p /var/www/html/storage/framework/{views,cache,sessions}
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

php artisan storage:link --force 2>/dev/null || true

echo "=== Starting nginx + php-fpm ==="

php-fpm -F 2>&1 &
echo "PHP-FPM started (pid $!)"
sleep 2

if [ -S /var/run/php-fpm.sock ]; then
    echo "PHP-FPM socket OK"
else
    echo "WARNING: PHP-FPM socket not found"
    ls -la /var/run/ 2>&1
fi

echo "Starting nginx on port $PORT..."
exec nginx -g 'daemon off;'
