ARG PHP_VERSION="8.1"

FROM composer:latest as composer

COPY composer.json composer.json
COPY composer.lock composer.lock

COPY database database
COPY tests tests

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

COPY  --chown=www-data:www-data . /app

RUN composer dump-autoload --optimize --classmap-authoritative

FROM php:${PHP_VERSION}-fpm-alpine

RUN apk update && \
    apk add \
        curl \
        libpng-dev \
        libxml2-dev \
        libmcrypt-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql sockets exif && docker-php-ext-enable pdo_mysql

COPY docker/app/php.ini   $PHP_INI_DIR/conf.d/

WORKDIR /var/www/html

COPY --from=composer /app/ /var/www/html/


