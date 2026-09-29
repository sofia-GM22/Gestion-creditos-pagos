#!/bin/sh
echo "Ejecutando migraciones..."
php /var/www/html/artisan migrate --force

echo "Ejecutando seeders..."
php /var/www/html/artisan db:seed --force || echo "Seeder falló o ya se había ejecutado, continuando..."