FROM dunglas/frankenphp:php8.4-bookworm

RUN docker-php-ext-install mysqli pdo pdo_mysql
