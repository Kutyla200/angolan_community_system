# 1. Use PHP 8.2 with Apache
FROM php:8.2-apache

# 2. Install system dependencies & PHP extensions for MySQL and Images (GD)
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd

# 3. Install Node.js 20 (This is what runs Vite and Tailwind)
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 4. Enable Apache mod_rewrite for Laravel routes
RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .

# 5. Install PHP dependencies (Composer)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 6. Install JS dependencies & Build Assets
# This runs Vite, which looks at your tailwind.config.js and compiles everything
RUN npm install
RUN npm run build

# 7. Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Point Apache to Laravel's public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

EXPOSE 80