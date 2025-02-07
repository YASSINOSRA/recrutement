# Use PHP 8.2 FPM as base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    zip \
    libzip-dev \
    libexif-dev \
    libbcmath-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd mbstring pdo pdo_mysql exif bcmath zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel app files (except vendor)
COPY . .

# Set permissions for Laravel's storage and cache directories
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 777 storage bootstrap/cache

# Copy composer.json first and install dependencies
COPY composer.json composer.lock ./

# Install PHP dependencies with more memory
RUN composer clear-cache && \
    COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader

# Cache Laravel config
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan config:cache

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
