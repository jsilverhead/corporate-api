FROM php:8.3-cli-alpine3.19

WORKDIR /var/www/html

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions \
    intl \
    uopz \
    opcache \
    pdo_pgsql \
    redis \
    uuid \
    gd \
    xdebug-stable \
 > /dev/null
RUN rm /usr/bin/install-php-extensions

RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
