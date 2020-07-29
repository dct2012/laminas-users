FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y libicu-dev unzip zip && \
    pecl install xdebug

RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install intl pdo_mysql;

RUN docker-php-ext-enable xdebug; \
    echo "xdebug.var_display_max_depth = -1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.var_display_max_children = -1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.var_display_max_data = -1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www