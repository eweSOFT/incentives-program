FROM php:8.1.0-fpm

WORKDIR /app

RUN apt-get update

RUN usermod -u 1000 www-data

RUN apt-get -y install git zip libpq-dev

RUN docker-php-ext-install pdo

RUN curl -sL https://getcomposer.org/installer | php -- --install-dir /usr/bin --filename composer

RUN pecl install xdebug

CMD ["php-fpm"]