FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y unzip libzip-dev

RUN docker-php-ext-install mysqli zip

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app"]