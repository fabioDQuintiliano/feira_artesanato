FROM php:8.3-apache

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libwebp-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        exif \
        gd \
        intl \
        mbstring \
        mysqli \
        pdo_mysql \
        zip \
    && a2enmod headers rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY docker/apache.conf /etc/apache2/conf-available/feira.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/feira.ini
RUN a2enconf feira

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader

COPY . .
COPY docker/entrypoint.sh /usr/local/bin/feira-entrypoint

RUN mkdir -p \
        /var/www/html/arquivos \
        /var/www/html/images/upload \
        /var/www/html/tables \
        /var/www/html/admin/exe_system \
        /var/www/html/containers/exe_system \
    && touch /var/www/html/functions/__list_functions.php \
    && chmod +x /usr/local/bin/feira-entrypoint \
    && chown -R www-data:www-data \
        /var/www/html/arquivos \
        /var/www/html/images/upload \
        /var/www/html/tables \
        /var/www/html/admin/exe_system \
        /var/www/html/containers/exe_system \
        /var/www/html/functions/__list_functions.php

ENTRYPOINT ["feira-entrypoint"]
CMD ["apache2-foreground"]