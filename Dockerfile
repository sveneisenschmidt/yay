FROM composer:1
FROM php:7.2-apache

LABEL org.label-schema.name="yay" \
      org.label-schema.url="https://github.com/sveneisenschmidt/yay" \
      org.label-schema.vcs-url="https://github.com/sveneisenschmidt/yay.git"

ENV APACHE_DOCUMENT_ROOT /data
ENV COMPOSER_DISABLE_XDEBUG_WARN 1
ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /data

RUN apt -y update && \
    apt install -y \
        libicu-dev \
        libxml2-dev \
        zlib1g-dev && \
    docker-php-ext-install \
        pdo_mysql \
        intl \
        xml \
        dom \
        mbstring \
        opcache  \
        zip && \
    apt remove -y \
        libicu-dev \
        libxml2-dev \
        zlib1g-dev && \
    apt autoremove -y

RUN a2enmod rewrite

COPY ./ /data
COPY ./dist/apache2/vhost.conf /etc/apache2/sites-enabled/000-default.conf
COPY ./dist/php/php.ini $PHP_INI_DIR/conf.d/999-custom.ini
COPY --from=0 /usr/bin/composer /usr/bin/composer
    
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$version \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp \
    && mv /tmp/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini

RUN composer install

CMD ["./docker-run.dist.sh"]
