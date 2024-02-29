FROM php:8.3.3-fpm
RUN curl -sL https://deb.nodesource.com/setup_current.x | bash - && \
    apt-get install -y nodejs

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libpq-dev \
    git \
    curl \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www/html

COPY . /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install && npm i

#CMD php artisan migrate --force &&
#CMD php artisan serve
CMD php-fpm
#CMD ["tail", "-f", "/dev/null"]
