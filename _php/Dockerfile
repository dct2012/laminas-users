FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y \
        libicu-dev \
        unzip \
        zip

RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    intl \
    pdo_mysql

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www