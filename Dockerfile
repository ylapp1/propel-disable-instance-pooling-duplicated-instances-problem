FROM php:7.2-cli
RUN apt-get update && \
    apt-get install -y git && \
    apt-get install -y unzip && \
    docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /app
CMD php test.php
