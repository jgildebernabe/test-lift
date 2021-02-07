FROM php:7.4-fpm

# Install packages
RUN apt-get update && apt-get install -y git curl zip unzip \
    && pecl install xdebug-2.9.8 \
    && docker-php-ext-enable xdebug

# Configure PHP-FPM
COPY docker/php-fpm/php.ini $PHP_INI_DIR/conf.d/custom.ini

ADD . /var/www/html

# install composer version 2
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

WORKDIR /var/www/html
RUN composer install
