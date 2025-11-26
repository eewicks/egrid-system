FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl gd

# Copy project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose correct port
EXPOSE 8080

# Start Apache on port 8080
CMD sed -i "s/80/\${PORT}/" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT}/" /etc/apache2/sites-enabled/000-default.conf && \
    apachectl -D FOREGROUND
