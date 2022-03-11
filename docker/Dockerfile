FROM php:8.0-fpm as start

RUN apt-get update && apt-get install -y \
    git \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    librabbitmq-dev \
    libpng-dev \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    gd \
    && pecl install amqp \
    && docker-php-ext-enable amqp 

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash

WORKDIR /var/www/project

COPY ./entrypoint/start-symfony.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT "/entrypoint.sh"


FROM php:8.0-fpm as consume

RUN apt-get update && apt-get install -y \
    git \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    librabbitmq-dev \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    && pecl install amqp \
    && docker-php-ext-enable amqp

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash

WORKDIR /var/www/project

COPY ./entrypoint/consume.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT "/entrypoint.sh"

FROM node:12 as node

WORKDIR /var/www/project

COPY ./entrypoint/npm.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT "/entrypoint.sh"