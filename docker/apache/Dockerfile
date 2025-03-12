FROM php:8.1-apache

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy custom PHP configuration
COPY docker/apache/php.ini /usr/local/etc/php/

# Copy application source code
COPY src/ /var/www/html/
