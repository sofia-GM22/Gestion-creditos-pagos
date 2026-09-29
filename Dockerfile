FROM serversideup/php:8.2-fpm-nginx-bookworm

WORKDIR /var/www/html

USER root

COPY . .

COPY --chmod=755 docker/60-seed.sh /etc/entrypoint.d/60-seed.sh

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER www-data

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_OUTPUT_LEVEL=info

EXPOSE 8080