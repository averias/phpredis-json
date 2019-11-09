FROM php:7.2-cli
RUN pecl install redis-5.0.1 \
    && pecl install xdebug-2.6.0 \
    && docker-php-ext-enable redis xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /app
CMD ["/bin/bash", "-c", "sleep infinity"]