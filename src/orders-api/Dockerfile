FROM php:8.2-fpm
WORKDIR /var/www

#Project files
COPY ./src/orders-api .

#Install libraries
RUN apt-get update && apt-get install -y libpng-dev zip unzip git curl python3 python3-pip
RUN docker-php-ext-install pdo pdo_mysql

#Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the entrypoint script into the container
COPY ./scripts/setupenv.py /usr/local/bin/
RUN chmod +x /usr/local/bin/setupenv.py
