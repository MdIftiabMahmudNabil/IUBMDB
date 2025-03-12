FROM php:8.1-apache

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip

RUN a2enmod rewrite

# Copy config
COPY php.ini /usr/local/etc/php/

# Copy app files (we're baking it into the image)
COPY ../../src/ /var/www/html/
