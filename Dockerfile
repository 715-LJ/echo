FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    git \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

WORKDIR /var/www/html
COPY ./api /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

COPY ./config/supervisor/supervisord.conf /etc/supervisord.conf

CMD ["php-fpm"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
