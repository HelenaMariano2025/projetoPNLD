FROM php:8.3-cli

RUN docker-php-ext-install mysqli \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /app

COPY . .

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app"]