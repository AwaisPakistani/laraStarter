FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
        git \
        unzip \
        curl \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        && docker-php-ext-configure gd \
            --with-freetype \
            --with-jpeg \
        && docker-php-ext-install \
            pdo_mysql \
            pcntl \
            gd \
            zip \
        && pecl install redis \
        && docker-php-ext-enable redis
COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
# Install Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --prefer-dist

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]