FROM php:8.4-fpm-alpine

# Устанавливаем зависимости системы
RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    postgresql-dev \
    zip \
    unzip \
    git \
    curl

# Устанавливаем PHP-расширения
RUN docker-php-ext-install pdo pdo_pgsql

# Копируем проект
WORKDIR /app
COPY . .

# Устанавливаем зависимости
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Настраиваем порт
EXPOSE 8080

# Запускаем Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080