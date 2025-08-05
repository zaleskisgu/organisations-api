# Разворачивание Organizations API с Docker

## Предварительные требования

- Docker
- Docker Compose

## Быстрый запуск

### 1. Клонирование и настройка

```bash
# Клонируйте репозиторий
git clone <repository-url>
cd organizations-api

# Скопируйте файл окружения
cp .env.example .env
```

### 2. Настройка переменных окружения

Отредактируйте файл `.env`:

```env
APP_NAME="Organizations API"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=organizations_api
DB_USERNAME=organizations_user
DB_PASSWORD=password

API_KEY=test-api-key-12345
```

### 3. Запуск контейнеров

```bash
# Сборка и запуск контейнеров
docker-compose up -d --build

# Проверка статуса контейнеров
docker-compose ps
```

### 4. Настройка базы данных

```bash
# Выполнение миграций
docker-compose exec app php artisan migrate

# Заполнение тестовыми данными
docker-compose exec app php artisan db:seed

# Генерация Swagger документации
docker-compose exec app php artisan l5-swagger:generate
```

### 5. Проверка работы

Приложение будет доступно по адресу: http://localhost:8000

- API: http://localhost:8000/api
- Swagger документация: http://localhost:8000/api/documentation

## Управление контейнерами

```bash
# Остановка контейнеров
docker-compose down

# Просмотр логов
docker-compose logs -f app

# Перезапуск контейнеров
docker-compose restart

# Удаление контейнеров и данных
docker-compose down -v
```

## Структура контейнеров

- **app** - Laravel приложение (PHP 8.2 + FPM)
- **webserver** - Nginx веб-сервер
- **db** - MySQL 8.0 база данных

## Полезные команды

```bash
# Вход в контейнер приложения
docker-compose exec app bash

# Выполнение Artisan команд
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan l5-swagger:generate

# Просмотр логов
docker-compose logs app
docker-compose logs webserver
docker-compose logs db

# Очистка кэша
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

## Устранение неполадок

### Проблема с правами доступа
```bash
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R 755 /var/www/storage
```

### Проблема с базой данных
```bash
# Проверка подключения к БД
docker-compose exec app php artisan tinker
# В tinker: DB::connection()->getPdo();
```

### Проблема с Swagger
```bash
# Перегенерация документации
docker-compose exec app php artisan l5-swagger:generate
```

## Производительность

Для продакшена рекомендуется:

1. Настроить кэширование конфигурации
2. Оптимизировать автозагрузчик Composer
3. Настроить Redis для кэширования
4. Использовать CDN для статических файлов

```bash
# Оптимизация для продакшена
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app composer install --optimize-autoloader --no-dev
``` 