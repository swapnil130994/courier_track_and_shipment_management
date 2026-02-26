FROM php:8.2

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql

COPY . .

# Run composer AFTER installation
RUN /usr/local/bin/composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]