FROM php:8.4-cli-bookworm

# Install required system packages and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . .

# Setup environment file and install dependencies
RUN cp .env.example .env \
    && composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs \
    && php artisan key:generate --force

# Create storage directories and set permissions
RUN mkdir -p storage/app/public/verifikasi \
             storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 8080

CMD ["sh", "-c", "php artisan storage:link && (php artisan migrate --force || true) && (php artisan db:seed --force || true) && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
