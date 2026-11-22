# ==============================================================================
# Estágio 1: Instalação de Dependências com Composer (Build Stage)
# ==============================================================================
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs

# ==============================================================================
# Estágio 2: Imagem Final de Execução em Produção (Runtime Stage)
# ==============================================================================
FROM php:8.4-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip \
    libzip-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install mysqli zip curl \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copia dependências geradas no estágio de build
COPY --from=vendor /app/vendor /app/vendor

# Copia o código-fonte da aplicação
COPY . /app

# Porta configurável para provedores em nuvem (Render, Railway, Fly.io)
EXPOSE 8000
ENV PORT=8000

# Inicializa o servidor web embutido utilizando o roteador com suporte a /health
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8000} -t /app /app/router.php"]
