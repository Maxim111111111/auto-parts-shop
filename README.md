# Auto Parts Shop Admin

Небольшая админка для магазина автозапчастей на Laravel 12 + AdminLTE.

## Что уже есть

- Дашборд с базовыми метриками склада.
- CRUD категорий запчастей.
- CRUD запчастей (SKU, бренд, цена, остаток, статус активности).
- Поиск и фильтры по запчастям.
- Демо-сидеры для быстрого старта.

## Основные разделы

- `/` — дашборд.
- `/categories` — категории.
- `/parts` — запчасти.

## Быстрый запуск

### 1) Установить зависимости

```bash
composer install
npm install
```

### 2) Поднять БД (вариант с Docker)

```bash
docker compose up -d
```

По умолчанию проект ожидает MySQL:

- `DB_HOST=db`
- `DB_PORT=3306`
- `DB_DATABASE=shop`
- `DB_USERNAME=root`
- `DB_PASSWORD=root`

### 3) Подготовить приложение

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 4) Запустить приложение

```bash
php artisan serve
npm run dev
```

## Тесты

```bash
composer test
```

## Демо-данные

Сидер `DatabaseSeeder` создаёт:

- 5 категорий;
- 5 запчастей;
- пользователя `test@example.com`.

## Deploy на Render

В проект добавлен `render.yaml` (Blueprint) и production `Dockerfile`.

### 1) Подготовить репозиторий

Убедитесь, что в репозитории есть файлы:

- `render.yaml`
- `Dockerfile`
- `scripts/render-start.sh`

### 2) Создать сервис через Blueprint

В Render:

1. New + -> Blueprint
2. Подключить репозиторий
3. Render создаст:
   - web service `auto-parts-shop`
   - postgres database `auto-parts-shop-db`

### 3) Обязательные переменные

Задайте в web service:

- `APP_KEY` (сгенерировать локально: `php artisan key:generate --show`)
- `APP_URL` (ваш Render URL)

Остальные переменные подтягиваются из `render.yaml`.

### 4) Первый запуск

Стартовый скрипт:

- прогоняет `php artisan migrate --force`;
- собирает и кеширует конфиги/роуты/вью;
- запускает приложение на `$PORT`.

Проверка здоровья: `GET /auth/me`.
