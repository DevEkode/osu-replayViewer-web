FROM php:7.3-apache

MAINTAINER DevEkode

# Copy applications files to docker folder app
COPY . /app

# Copy vhost.conf to docker
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Install and execute final steps
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git zip unzip nodejs

RUN chown -R www-data:www-data /app \
    && a2enmod rewrite

RUN curl -sL https://deb.nodesource.com/setup_13.x | bash -

RUN curl --silent --show-error https://getcomposer.org/installer | php \
    && cd /app \
    && /var/www/html/composer.phar install \
    && npm install