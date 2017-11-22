FROM php:7.2-rc-apache

LABEL org.label-schema.name="yay" \
      org.label-schema.url="https://github.com/sveneisenschmidt/yay" \
      org.label-schema.vcs-url="https://github.com/sveneisenschmidt/yay.git"

RUN apt-get -y update && \
    apt-get install -y \
        libicu-dev \
        libxml2-dev \
        zlib1g-dev

RUN docker-php-ext-install \
    pdo_mysql \
    intl \
    xml \
    dom \
    mbstring \
    opcache \
    zip

COPY ./ /var/www/html
COPY ./dist/apache2/vhost.conf /etc/apache2/sites-enabled/99-vhost.conf
RUN a2enmod rewrite

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer && \
    php -r "unlink('composer-setup.php');" 
ENV COMPOSER_DISABLE_XDEBUG_WARN 1
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install --ignore-platform-reqs

CMD ["./docker-run.dist.sh"]