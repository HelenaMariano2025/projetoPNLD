FROM php:8.4-cli AS extensions

RUN apt-get update \
    && apt-get install -y libzip-dev libcurl4-openssl-dev libonig-dev \
    && docker-php-ext-install mysqli pdo_mysql mbstring zip curl \
    && rm -rf /var/lib/apt/lists/*

FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip5 \
    && rm -rf /var/lib/apt/lists/*

COPY --from=extensions /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=extensions /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

WORKDIR /app

COPY . .

EXPOSE 8080

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t /app /app/router.php"]
