FROM php:8.3.0-fpm-alpine

RUN apk add --no-cache \
    linux-headers \
    curl \
    zip \
    unzip \
    build-base \
    autoconf \
    bash \
    oniguruma-dev \
    zlib-dev \
    mysql-client \
    libxml2-dev \
    libzip-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    freetype-dev\
    rabbitmq-c-dev \
    && pecl install amqp \
    && docker-php-ext-install bcmath sockets pdo pdo_mysql zip gd \
    && docker-php-ext-enable amqp

COPY ./wait-for-it.sh /usr/local/bin/wait-for-it
COPY ./php.ini /usr/local/etc/php
RUN chmod 555 /usr/local/bin/wait-for-it

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG INSTALL_XDEBUG=false

RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
       pecl install xdebug  && docker-php-ext-enable xdebug; \
    fi;

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

ARG PUID=1000
ARG PGID=1000

RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

EXPOSE 9000

CMD ["php-fpm"]

WORKDIR /var/www/html
