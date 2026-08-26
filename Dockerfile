# ==============================================================================
# Stage 1: Build Frontend Assets with Node.js
# ==============================================================================
FROM node:20-alpine AS frontend
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

# ==============================================================================
# Stage 2: Install Composer Dependencies
# ==============================================================================
FROM composer:2 AS composer_build
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist --optimize-autoloader

# ==============================================================================
# Stage 3: Production Runtime (PHP-FPM + Nginx + Supervisor)
# ==============================================================================
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    gettext \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    postgresql-dev \
    sqlite-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        zip

# Copy Composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application source code
COPY . .

# Copy vendor from composer build stage
COPY --from=composer_build /app/vendor ./vendor

# Copy built frontend assets from frontend stage
COPY --from=frontend /app/public/build ./public/build

# Setup config files
COPY docker/nginx.conf.template /etc/nginx/nginx.conf.template
COPY docker/php.ini $PHP_INI_DIR/conf.d/custom.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Set correct permissions
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Environment defaults
ENV PORT=80
ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 80 10000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
