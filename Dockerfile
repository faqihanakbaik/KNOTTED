
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
FROM php:8.5-apache

# Install system dependencies and build tools
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    cmake \
    wget \
    unzip \
    git \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Build and install lexbor library from source
RUN cd /tmp \
    && wget https://github.com/lexbor/lexbor/archive/refs/tags/v2.3.0.tar.gz \
    && tar -xzf v2.3.0.tar.gz \
    && cd lexbor-2.3.0 \
    && cmake . \
    && make \
    && make install \
    && ldconfig \
    && cd / \
    && rm -rf /tmp/lexbor-2.3.0 /tmp/v2.3.0.tar.gz

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        mbstring \
        bcmath \
        curl \
        gd \
        zip \
        exif \
        pcntl \
        dom \
        simplexml \
        xml

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel source code
COPY . .

# Install production dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Copy built frontend assets
COPY --from=frontend /app/public/build ./public/build

# Configure Apache
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
        > /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite headers

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
