# ==========================================
# KNOTTED - Laravel 12 Production
# PHP 8.4 + Apache + Supabase
# ==========================================

# ------------------------------------------
# STAGE 1: Build frontend assets
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

# Install GD extension
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_PROCESS_TIMEOUT=600

WORKDIR /var/www/html

# Copy Laravel application
COPY . .

# Install production dependencies with retry
RUN set -eux; \
    composer config --global process-timeout 600; \
    composer config --global max-parallel-http 1; \
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
            echo "Composer installation failed after 3 attempts."; \
            exit 1; \
        fi; \
        echo "Composer download failed. Retrying in 10 seconds..."; \
        sleep 10; \
    done

# Run Laravel package discovery manually
RUN php artisan package:discover --ansi

# Copy built frontend assets
COPY --from=frontend /app/public/build ./public/build

# ------------------------------------------
# APACHE CONFIGURATION
# ------------------------------------------

# Remove all enabled MPM modules, then enable only prefork
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
          /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork rewrite headers \
    && apache2ctl -M 2>&1 | grep mpm_ \
    && test "$(apache2ctl -M 2>&1 | grep -Ec 'mpm_[a-z]+_module')" -eq 1

# Configure Apache to listen on port 8080
RUN sed -i 's/^Listen 80$/Listen 8080/' /etc/apache2/ports.conf \
    && printf '%s\n' \
        '<VirtualHost *:8080>' \
        '    DocumentRoot /var/www/html/public' \
        '    <Directory /var/www/html/public>' \
        '        AllowOverride All' \
        '        Require all granted' \
        '    </Directory>' \
        '    ErrorLog ${APACHE_LOG_DIR}/error.log' \
        '    CustomLog ${APACHE_LOG_DIR}/access.log combined' \
        '</VirtualHost>' \
        > /etc/apache2/sites-available/000-default.conf

# ------------------------------------------
# LARAVEL PERMISSIONS
# ------------------------------------------

RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ------------------------------------------
# START APPLICATION
# ------------------------------------------

EXPOSE 8080

CMD ["apache2-foreground"]
