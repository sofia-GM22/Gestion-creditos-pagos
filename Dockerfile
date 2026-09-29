FROM serversideup/php:8.2-fpm-nginx-bookworm

WORKDIR /var/www/html

COPY . .

# Ejecutamos directamente composer install
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_OUTPUT_LEVEL=info

EXPOSE 8080