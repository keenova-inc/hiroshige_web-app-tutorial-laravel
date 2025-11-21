
FROM php:8.2-fpm-alpine

# composerコマンドのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache \
    $PHPIZE_DEPS \
    libzip-dev \
    sqlite-dev \
    mysql-client \
    curl \
    git \
    unzip

RUN docker-php-ext-install pdo pdo_mysql opcache zip

WORKDIR /var/www/html