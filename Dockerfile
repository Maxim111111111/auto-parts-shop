FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM php:8.4-cli-bookworm AS app

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install \
    bcmath \
    gd \
    pdo_mysql \
    pdo_pgsql \
    zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
COPY --from=frontend /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && chmod +x scripts/render-start.sh \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

EXPOSE 10000

CMD ["bash", "scripts/render-start.sh"]
