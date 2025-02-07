FROM php:8.2-fpm

WORKDIR /var/www/html

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

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 777 storage bootstrap/cache

COPY composer.json composer.lock ./

RUN composer clear-cache && \
    COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader

RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan config:cache

EXPOSE 9000

CMD ["php-fpm"]
