
# ==========================================
# KNOTTED - Laravel 12 + PHP 8.5
# Production Docker Configuration
# ==========================================

# Stage 1: Build frontend assets
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# Stage 2: Laravel application
FROM php:8.4-apache

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

# Install PHP extensions in groups to avoid timeout
RUN docker-php-ext-install -j$(nproc) pdo_pgsql pgsql
RUN docker-php-ext-install -j$(nproc) mbstring bcmath
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install -j$(nproc) zip exif pcntl
RUN docker-php-ext-install -j$(nproc) dom simplexml xml

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel source code
COPY . .

# Set Composer environment
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install production dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-progress \
    --no-scripts

# Copy built frontend assets
COPY --from=frontend /app/public/build ./public/build

# Configure Apache - Force exactly one MPM
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
          /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork rewrite headers \
    && apache2ctl -M 2>&1 | grep mpm_ \
    && test "$(apache2ctl -M 2>&1 | grep -c 'mpm_.*_module')" -eq 1

# Configure Apache port and virtual host
RUN sed -i 's/^Listen 80$/Listen 10000/' /etc/apache2/ports.conf \
    && printf '%s\n' \
        '<VirtualHost *:10000>' \
        'DocumentRoot /var/www/html/public' \
        '<Directory /var/www/html/public>' \
        'AllowOverride All' \
        'Require all granted' \
        '</Directory>' \
        'ErrorLog ${APACHE_LOG_DIR}/error.log' \
        'CustomLog ${APACHE_LOG_DIR}/access.log combined' \
        '</VirtualHost>' \
        > /etc/apache2/sites-available/000-default.conf

# Prepare Laravel writable directories
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Render port
EXPOSE 10000

CMD ["apache2-foreground"]
