FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo_pgsql pdo_mysql mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD php artisan migrate --force && php artisan route:clear && php artisan config:clear && php artisan cache:clear && php artisan serve --host=0.0.0.0 --port=8000