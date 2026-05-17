#!/bin/sh
set -e

cd /var/www

echo "Bootstrapping Laravel..."

# -----------------------------------
# 1. Install Laravel if missing
# -----------------------------------
if [ ! -f artisan ]; then
    echo "Laravel not found. Installing..."

    rm -rf temp

    composer create-project laravel/laravel temp

    echo "Moving Laravel files..."

    mv temp/* .
    mv temp/.[!.]* . 2>/dev/null || true

    rm -rf temp
fi

# -----------------------------------
# 2. Apply Docker .env only if .env is missing or empty
# -----------------------------------
if [ ! -s .env ]; then
    echo ".env not found or empty. Copying .env.docker..."
    cp .env.docker .env
else
    echo ".env already exists and is not empty. Skipping .env.docker copy."
fi

# -----------------------------------
# 3. Install dependencies
# -----------------------------------

echo "Installing dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader


# -----------------------------------
# 4. Fix permissions
# -----------------------------------
echo "Fixing permissions..."
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# -----------------------------------
# 5. Generate app key if missing
# -----------------------------------
if ! grep -q "^APP_KEY=base64" .env; then
    echo "Generating app key..."
    php artisan key:generate --force
fi

# -----------------------------------
# 6. Clear cached Laravel bootstrap/config
# -----------------------------------
echo "Clearing Laravel caches..."
php artisan optimize:clear || true

# -----------------------------------
# 7. Wait for PostgreSQL
# -----------------------------------
echo "Waiting for PostgreSQL..."

until php -r "
try {
    new PDO('pgsql:host=postgres;port=5432;dbname=laravel_db', 'user', 'user123');
    echo \"DB connected\n\";
} catch (Exception \$e) {
    exit(1);
}
"; do
    sleep 2
done

# -----------------------------------
# 8. Run migrations in background
# -----------------------------------
(
    echo "Running migrations..."
    php artisan migrate --force || true

    echo "Clearing config cache..."
    php artisan config:clear || true
) &

# -----------------------------------
# 9. Start PHP-FPM
# -----------------------------------
echo "Starting PHP-FPM..."
exec php-fpm -F
