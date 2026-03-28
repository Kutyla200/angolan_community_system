# Use PHP 8.2 with Apache
FROM php:8.2-apache

# 1. Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

# 2. Install Node.js (Required to run Vite)
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Enable Apache mod_rewrite for Laravel routes
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy your project files
COPY . .

# 3. Install Composer and PHP dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 4. Install NPM dependencies and Build Vite Assets
# This creates the public/build/manifest.json file Laravel is looking for
RUN npm install
RUN npm run build

# 5. Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Point Apache to the Laravel 'public' folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

EXPOSE 80