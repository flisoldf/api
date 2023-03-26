FROM php:7.4-fpm-alpine

COPY php.ini $PHP_INI_DIR/php.ini

# Composer Dependencies
RUN apk --update add \
    bzip2-dev \
    autoconf \
    build-base \
    curl \
    git \
    subversion \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    libbz2 \
    oniguruma-dev \
    libstdc++ \
    libxslt-dev \
    openldap-dev \
    make \
    zip \
    unzip \
    wget && \
    docker-php-ext-install zip bz2 mbstring pcntl xsl && \
    docker-php-ext-install gd && \
    docker-php-ext-configure ldap --with-libdir=lib/ && \
    docker-php-ext-install ldap && \
    docker-php-ext-install pdo pdo_mysql mysqli && \
    apk del build-base && \
    rm -rf /var/cache/apk/*

# Register the COMPOSER_HOME environment variable
ENV COMPOSER_HOME /composer

# Add global binary directory to PATH and make sure to re-export it
ENV PATH /composer/vendor/bin:$PATH

# Allow Composer to be run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Setup the Composer installer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
  && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer \
  && rm -rf /tmp/composer-setup.php

## XDebug
## This example is extremely barebones and specific to Alpine linux, however in most containers at/below
## php 7.4, you can just run pecl + docker-php-ext-enable (see below) after you've ensured that you have
## also installed phpize (necessary for building PHP extensions).
#RUN apk --update add \
#    g++ && \
#    pecl install xdebug-2.9.2 && \
#    docker-php-ext-enable xdebug && \
#    # Clean up...
#    apk del g++
#
## Copy over our custom xdebug INI configuration which will coexist with the automatically generated
## $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini created by the "docker-php-ext-enable xdebug" command above.
#COPY ./conf.d/xdebug.ini $PHP_INI_DIR/conf.d
#
## Enable show all errors
#COPY ./conf.d/error_reporting.ini $PHP_INI_DIR/conf.d

# Set up the working directory
WORKDIR /var/www/app
