FROM php:8.2

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo pdo_mysql

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]