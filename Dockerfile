FROM php:8.2-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Fix ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install system packages + Node.js for Vite
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl gd \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install backend dependencies
RUN composer install --no-dev --optimize-autoloader

# Build Vite assets
RUN npm install
RUN npm run build

# VERY IMPORTANT: Ensure assets exist
RUN mkdir -p public/assets
RUN cp -r resources/assets/* public/assets/ 2>/dev/null || true

# LINK STORAGE (fix broken images, CSS, JS, assets)
RUN php artisan storage:link || true

# Cache configs for production
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache public

# Point Apache to Laravel public directory
RUN sed -i 's#/var/www/html#/var/www/html/public#' /etc/apache2/sites-available/000-default.conf

EXPOSE 8080

# Railway port mapping
CMD sed -i "s/80/${PORT}/" /etc/apache2/ports.conf \
    && sed -i "s/:80/:${PORT}/" /etc/apache2/sites-enabled/000-default.conf \
    && apachectl -D FOREGROUND
