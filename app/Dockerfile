FROM dunglas/frankenphp:1.2-php8.3

WORKDIR /app
COPY . /app
RUN install-php-extensions pdo_mysql gd zip opcache && \
    composer install --no-interaction --prefer-dist --optimize-autoloader && \
    php artisan key:generate && \
    php artisan storage:link || true

ENV SERVER_NAME=:80
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

