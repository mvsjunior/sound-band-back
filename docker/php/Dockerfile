FROM php:8.3-fpm

ARG TZ=America/Sao_Paulo
ENV TZ=${TZ}

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    tzdata \
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

# Configura timezone do sistema
RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo ${TZ} > /etc/timezone

# PHP timezone
RUN echo "date.timezone=${TZ}" > /usr/local/etc/php/conf.d/timezone.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Usuário
RUN useradd -G www-data -u 1000 -d /home/laravel laravel
RUN mkdir -p /home/laravel/.composer && chown -R laravel:laravel /home/laravel


WORKDIR /var/www/html

USER laravel
