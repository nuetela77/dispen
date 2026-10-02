FROM php:8.4-cli-alpine

# Official PHP extension installer for bulletproof compilation
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --no-cache git curl zip unzip \
    && install-php-extensions pdo_mysql gd zip bcmath pcntl opcache

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files (vendor & node_modules excluded by .dockerignore)
COPY . .

# Prepare environment and install dependencies
RUN cp .env.example .env && \
    composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs && \
    php artisan key:generate --force

# Ensure storage directories exist and have proper permissions
RUN mkdir -p storage/app/public/verifikasi storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 8080

CMD sh -c "php artisan storage:link && (php artisan migrate --force || true) && (php artisan db:seed --force || true) && php artisan serve --host=0.0.0.0 --port=\${PORT:-8080}"
