FROM php:8.2-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nodejs \
    npm

# Очистка кэша
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка PHP расширений
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Создание рабочей директории
WORKDIR /var/www

# Копирование файлов проекта
COPY . .

# Установка зависимостей PHP
RUN composer install --no-dev --optimize-autoloader

# Установка зависимостей Node.js (если package.json существует)
RUN if [ -f "package.json" ]; then npm install && npm run build; fi

# Установка прав доступа
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Копирование конфигурации PHP
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

EXPOSE 9000

CMD ["php-fpm"] 