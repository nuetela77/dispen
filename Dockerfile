FROM php:8.4-cli-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring gd pcntl bcmath opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Ensure storage directories exist and have proper permissions
RUN mkdir -p storage/app/public/verifikasi storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 8080

CMD sh -c "if [ -z \"\$APP_KEY\" ]; then php artisan key:generate --force; fi && php artisan storage:link && (php artisan migrate --force || true) && (php artisan db:seed --force || true) && php artisan serve --host=0.0.0.0 --port=\${PORT:-8080}"
