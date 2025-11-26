FROM php:8.2-apache

# Enable Apache Rewrite
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl gd

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Fix storage permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Copy Laravel .env from Railway environment
RUN echo "APP_KEY=${APP_KEY}" >> .env \
    && echo "APP_ENV=${APP_ENV}" >> .env \
    && echo "APP_DEBUG=${APP_DEBUG}" >> .env \
    && echo "APP_URL=${APP_URL}" >> .env \
    && echo "DB_CONNECTION=${DB_CONNECTION}" >> .env \
    && echo "DB_HOST=${DB_HOST}" >> .env \
    && echo "DB_PORT=${DB_PORT}" >> .env \
    && echo "DB_DATABASE=${DB_DATABASE}" >> .env \
    && echo "DB_USERNAME=${DB_USERNAME}" >> .env \
    && echo "DB_PASSWORD=${DB_PASSWORD}" >> .env

# Clear and cache Laravel config
RUN php artisan config:clear
RUN php artisan config:cache

# Expose PORT
EXPOSE 8080

# Make Apache listen to Railway PORT
CMD sed -i "s/80/${PORT}/" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT}/" /etc/apache2/sites-enabled/000-default.conf && \
    apachectl -D FOREGROUND
