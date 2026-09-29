FROM serversideup/php:8.2-fpm-nginx-bookworm

WORKDIR /var/www/html

# Cambiar temporalmente a root para asegurar permisos en el build
USER root

COPY . .

# Dar permisos usando las herramientas del sistema y luego composer
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Volver al usuario web por seguridad
USER www-data

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_OUTPUT_LEVEL=info

EXPOSE 8080