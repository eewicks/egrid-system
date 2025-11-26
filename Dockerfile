FROM php:8.2-apache

# Enable Apache Rewrite
RUN a2enmod rewrite

# Install dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl gd

# Set working directory
WORKDIR /var/www/html

# Copy app
COPY . .

# Copy composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel folder permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Apache uses public folder
RUN sed -i 's#/var/www/html#/var/www/html/public#' /etc/apache2/sites-available/000-default.conf

# Disable artisan serve — we use Apache ONLY
EXPOSE 8080

# Tell Apache to run on Railway's port
CMD sed -i "s/80/${PORT}/" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT}/" /etc/apache2/sites-enabled/000-default.conf && \
    apachectl -D FOREGROUND
