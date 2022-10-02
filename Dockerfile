FROM php:8.1-fpm

WORKDIR /app

RUN apt-get update -y && \
  apt-get install -y g++ libicu-dev libpq-dev libzip-dev zlib1g-dev zip unzip git openssl && \ 
  docker-php-ext-install intl opcache pdo pdo_pgsql pgsql && \
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
  curl -fsSL https://deb.nodesource.com/setup_16.x | bash - && \
  apt-get install -y nodejs && \
  npm install -g npm

COPY . .

COPY ./production.env ./.env

RUN composer install && npm ci && npm run production

RUN usermod -u 1000 www-data

COPY --chown=www-data:www-data . /app

USER www-data

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
