FROM php:8.2

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    unzip git \
    && docker-php-ext-install pdo pdo_mysql

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]