FROM composer:2.6.6 AS composer

WORKDIR /var/www/html

COPY ./app/composer.json .
COPY ./app/composer.lock .
RUN composer install --no-interaction --no-progress --no-scripts --no-plugins --quiet --no-autoloader --ignore-platform-reqs --no-dev

COPY ./app/src ./src
RUN composer dump-autoload --classmap-authoritative --no-scripts --no-interaction

FROM php:8.3-fpm-alpine3.19

WORKDIR /var/www/html

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions \
    intl \
    opcache \
    pdo_pgsql \
    redis \
    uuid \
    gd \
    uopz \
 > /dev/null
RUN rm /usr/bin/install-php-extensions

COPY --from=composer /var/www/html/vendor/ /var/www/html/vendor/
COPY ./app /var/www/html

RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY ./docker/php/fpm/php.ini $PHP_INI_DIR/conf.d/php-app.ini
COPY ./docker/php/fpm/php-fpm.conf $PHP_INI_DIR/../php-fpm.d/zzz-app.conf

ARG APP_VERSION
ARG API_VERSION

ENV APP_VERSION=$APP_VERSION
ENV API_VERSION=$API_VERSION

USER www-data
