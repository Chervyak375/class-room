FROM php:7.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --version=1.10.21 \
        --filename=composer.phar \
        --install-dir=/usr/local/bin && \
    php /usr/local/bin/composer.phar clear-cache

#RUN php /usr/local/bin/composer.phar install