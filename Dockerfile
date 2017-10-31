FROM php:7.2-rc-alpine

ENV COMPOSER_DISABLE_XDEBUG_WARN 1
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apk add --no-cache --virtual .persistent-deps \
    bash \
    curl \
    icu-dev \
    libmcrypt-dev \
    libxml2-dev

RUN docker-php-ext-install \
    pdo_mysql \
    intl \
    xml \
    dom \
    mbstring \
    opcache \
    zip

RUN echo "memory_limit=-1" > $PHP_INI_DIR/conf.d/memory.ini

RUN cd /tmp && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer && \
    php -r "unlink('composer-setup.php');" && \
    rm -rf /tmp/*

COPY ./ ./data

WORKDIR /data

RUN composer install --ignore-platform-reqs
