FROM php:8.3-fpm

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Usuário
RUN useradd -G www-data -u 1000 -d /home/laravel laravel
RUN mkdir -p /home/laravel/.composer && chown -R laravel:laravel /home/laravel

WORKDIR /var/www/html

USER laravel


