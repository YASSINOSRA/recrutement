# Use an official PHP image with FPM (FastCGI Process Manager)
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
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd mbstring pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the Laravel project files to the container
COPY . .

# Set the correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set environment variables for Laravel
ARG APP_ENV=production
ENV APP_ENV=${APP_ENV}

# Set writable permissions for storage and bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# Clear and cache Laravel configuration
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan config:cache

# Expose the default Laravel port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
