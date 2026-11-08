# ==========================================
# KNOTTED - Laravel 12 Production
# PHP 8.4 CLI + Supabase + Railway
# NO APACHE
# ==========================================

# ------------------------------------------
# STAGE 1: Build frontend
# ------------------------------------------
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# ------------------------------------------
# STAGE 2: Laravel application
# ------------------------------------------
FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    unzip \
    git \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    mbstring \
    bcmath \
    zip \
    exif \
    pcntl \
    dom \
    simplexml \
    xml

# Install GD
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_PROCESS_TIMEOUT=600

WORKDIR /var/www/html

# Copy application
COPY . .

# Install Composer dependencies with retry
RUN set -eux; \
    composer config --global process-timeout 600; \
    for attempt in 1 2 3; do \
        if composer install \
            --no-dev \
            --no-interaction \
            --prefer-dist \
            --optimize-autoloader \
            --no-progress \
            --no-scripts; then \
            break; \
        fi; \
        if [ "$attempt" -eq 3 ]; then \
            exit 1; \
        fi; \
        echo "Retrying Composer installation..."; \
        sleep 10; \
    done

# Laravel package discovery
RUN php artisan package:discover --ansi

# Copy frontend build
COPY --from=frontend /app/public/build ./public/build

# Prepare Laravel directories
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Railway port
EXPOSE 8080

# Start Laravel using Railway PORT
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
