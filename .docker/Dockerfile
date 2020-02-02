# Stage 1
FROM php:7.3-apache AS builder

MAINTAINER DevEkode

# Install and execute final steps
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git zip unzip nodejs npm

RUN curl --silent --show-error https://getcomposer.org/installer | php

COPY ./composer.json /app/composer.json
COPY ./package.json /app/package.json

RUN cd /app \
    && /var/www/html/composer.phar install \
    && npm install

# Stage 2
FROM php:7.3-apache AS base

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev

# Configure PHP
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install zip

# Stage 3
FROM base AS app

COPY . /app
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/apache/php.ini-production "$PHP_INI_DIR/php.ini"

# Remove .docker and useless folders in container
RUN rm -rf /app/.docker
RUN rm -rf /app/.idea
RUN rm -rf /app/.github
RUN rm -rf /app/.ssh
RUN rm -rf /app/.git

# Remove useless files in container
RUN rm -f /app/.env.example
RUN rm -f /app/docker-compose-dev.yml
RUN rm -f /app/docker-compose.yml
RUN rm -f /app/.gitignore
RUN rm -f /app/composer.json
RUN rm -f /app/composer.lock
RUN rm -f /app/package-lock.json
RUN rm -f /app/package.json

COPY --from=builder /app/node_modules /app/node_modules
COPY --from=builder /app/vendor /app/vendor

RUN a2enmod rewrite

RUN mkdir /app/accounts
RUN mkdir /app/requestList
RUN mkdir /app/php/patreon/pledgers
RUN mkdir /app/uploads

RUN chown -R www-data: /app