FROM serversideup/php:8.2-fpm-nginx-bookworm

WORKDIR /var/www/html

COPY . .

# 1. Dar permisos a storage y bootstrap/cache ANTES de correr composer
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 2. Ahora sí ejecutamos composer install con los permisos listos
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_OUTPUT_LEVEL=info

EXPOSE 8080