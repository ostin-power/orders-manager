FROM php:8.2-fpm
WORKDIR /var/www

#Project files
COPY ./src/orders-app .

#Install libraries
RUN apt-get update && apt-get install -y libpng-dev zip unzip git curl
RUN docker-php-ext-install pdo pdo_mysql

#Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install

#Startup
CMD php artisan serve --host=0.0.0.0 --port=8000
