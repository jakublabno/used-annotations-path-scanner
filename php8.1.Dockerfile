FROM composer:latest as composer
FROM php:8.1-cli-alpine

RUN apk update && \
    apk upgrade && \
    apk add --no-cache \
        autoconf \
        g++ \
        make

RUN pecl install xdebug-3.1.0 apcu && \
    pecl clear-cache && \
    docker-php-ext-enable xdebug

COPY build/php.ini /usr/local/etc/php/

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . /app

WORKDIR /app
