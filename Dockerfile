# ==============================================================================
# Estágio 1: Instalação de Dependências com Composer (Build Stage)
# ==============================================================================
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs

# ==============================================================================
# Estágio 2: Compilação de Extensões PHP (Extensions Stage)
# ==============================================================================
FROM php:8.4-cli AS extensions

RUN apt-get update \
    && apt-get install -y libzip-dev libcurl4-openssl-dev libonig-dev \
    && docker-php-ext-install mysqli pdo_mysql mbstring zip curl \
    && rm -rf /var/lib/apt/lists/*

# ==============================================================================
# Estágio 3: Imagem Final de Execução em Produção (Runtime Stage)
# ==============================================================================
FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip5 \
    && rm -rf /var/lib/apt/lists/*

COPY --from=extensions /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=extensions /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

WORKDIR /app

COPY --from=vendor /app/vendor /app/vendor
COPY *.php /app/
COPY css/ /app/css/
COPY fonts/ /app/fonts/
COPY images/ /app/images/
COPY includes/ /app/includes/
COPY js/ /app/js/
COPY php/ /app/php/
COPY sql/ /app/sql/

RUN chown -R www-data:www-data /app

USER www-data

ENV PORT=8080
EXPOSE 8080

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t /app /app/router.php"]
