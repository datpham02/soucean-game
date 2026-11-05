FROM composer:2 AS vendor

WORKDIR /var/www/html/backend

COPY backend ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-progress \
    --optimize-autoloader \
    --no-scripts

FROM php:8.4-fpm-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        unzip \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
        libsqlite3-dev \
    && docker-php-ext-install \
        bcmath \
        pcntl \
        pdo_mysql \
        pdo_sqlite \
        zip \
        sockets \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY --from=vendor --chown=www-data:www-data /var/www/html/backend /var/www/html/backend
COPY docker/app/nginx.conf /etc/nginx/nginx.conf
COPY docker/app/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint

RUN chmod +x /usr/local/bin/entrypoint \
    && mkdir -p /tmp/nginx /var/lib/nginx /var/log/nginx \
    && chown -R www-data:www-data /tmp/nginx /var/lib/nginx /var/log/nginx /var/www/html

WORKDIR /var/www/html/backend
USER www-data

EXPOSE 8080

ENTRYPOINT ["entrypoint"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
