FROM php:8.2-apache-bookworm

LABEL org.opencontainers.image.authors="Alexander Kharchenko <morontt@yandex.ru>"

VOLUME ["/var/www/html"]

ARG HOST_UID=1000

ENV TZ="Europe/Moscow"
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    nano \
    zip unzip \
    libzip-dev \
    mariadb-client \
    ; \
    apt-get clean && rm -rf /tmp/* /var/tmp/*

RUN docker-php-ext-install -j$(nproc) zip opcache pdo_mysql \
    && yes | pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && cp ${PHP_INI_DIR}/php.ini-development ${PHP_INI_DIR}/php.ini \
    && sed -i 's/;date.timezone =/date.timezone = Europe\/Moscow/' ${PHP_INI_DIR}/php.ini \
    && sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 7M/' ${PHP_INI_DIR}/php.ini \
    && sed -i 's/memory_limit = 128M/memory_limit = 512M/' ${PHP_INI_DIR}/php.ini \
    ;

RUN a2enmod rewrite remoteip && a2dismod deflate -f
RUN set -x && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

COPY ./.bashrc /root/.bashrc
RUN usermod -u ${HOST_UID} www-data && groupmod -g ${HOST_UID} www-data

EXPOSE 80
