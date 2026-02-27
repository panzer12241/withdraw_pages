# ─────────────────────────────────────────
# Stage 1: Build frontend assets with Bun
# ─────────────────────────────────────────
FROM oven/bun:1 AS frontend

WORKDIR /app

COPY package.json bun.lock* ./
RUN bun install --frozen-lockfile

COPY vite.config.js ./
COPY resources/ resources/
COPY public/ public/

RUN bun run build

# ─────────────────────────────────────────
# Stage 2: PHP-FPM production image
# ─────────────────────────────────────────
FROM php:8.4-fpm AS app

RUN apt-get update && apt-get install -y \
    bash git curl zip unzip \
    libpq-dev libzip-dev libpng-dev \
    libonig-dev libxml2-dev libicu-dev \
    && docker-php-ext-install \
        pdo pdo_pgsql pgsql \
        mbstring bcmath zip exif pcntl intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP dependencies (production only)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --optimize-autoloader

# Copy full application
COPY . .

# Copy built frontend assets from stage 1
COPY --from=frontend /app/public/build public/build

# Finalize composer
RUN composer dump-autoload --optimize

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
