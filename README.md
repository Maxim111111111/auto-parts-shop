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

