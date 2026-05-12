FROM php:8.4-fpm

RUN apt-get update && apt-get install -y curl unzip git libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer


WORKDIR /var/www/html
