# Shared Hosting Deploy

Проект: Laravel 12, PHP 8.2, Vite, Vue storefront, AdminLTE admin.

## Что требуется от хостинга

- PHP 8.2 или выше.
- MySQL 8+ или совместимая MariaDB.
- Возможность указать document root на папку `public`.
- Доступ по SSH желателен, но не обязателен.

Если document root нельзя направить в `public`, выкладывать Laravel на такой тариф неудобно и рискованно. В этом случае лучше выбрать VPS/PaaS или тариф с настройкой корневой папки сайта.

## Что важно именно для этого проекта

- Главная витрина открывается по `/`.
- Админка находится по `/admin`.
- Фронтенд собирается Vite в `public/build`.
- В git `public/build` исключён, поэтому при выкладке через FTP его нужно загрузить вместе с проектом.
- В продакшене лучше использовать MySQL и оставить простые драйверы:
  - `SESSION_DRIVER=file`
  - `CACHE_STORE=file`
  - `QUEUE_CONNECTION=sync`

## Вариант 1: на сервере есть SSH и Composer

### 1. Подготовить базу данных

Создайте на хостинге:

- базу данных;
- пользователя базы;
- пароль;
- доступ этого пользователя к базе.

### 2. Загрузить файлы проекта

Не загружайте локальные артефакты:

- `.env`
- `node_modules`
- `tmp/db`
- `shop`

### 3. Настроить `.env`

Скопируйте `.env.production.example` в `.env` и заполните:

- `APP_URL`
- `APP_KEY` после генерации ключа
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### 4. Выполнить команды на сервере

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Если нужны демо-данные:

```bash
php artisan db:seed --force
```

После сидирования будет создан админ:

- `test@example.com`
- `password`

Его лучше сразу заменить или удалить.

### 5. Собрать фронтенд

Если на сервере есть Node.js:

```bash
npm ci
npm run build
```

Если Node.js на сервере нет, соберите локально:

```bash
npm ci
npm run build
```

и загрузите папку `public/build` на сервер вручную.

## Вариант 2: только FTP, без Composer и Node.js

Этот вариант работает, только если у вас локально уже есть:

- папка `vendor`;
- папка `public/build`.

### 1. Собрать проект локально

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

### 2. Подготовить `.env`

Создайте `.env` из `.env.production.example` и заполните production-значения.

Ключ приложения можно сгенерировать локально:

```bash
php artisan key:generate --show
```

Вставьте полученное значение в `APP_KEY`.

### 3. Загрузить на сервер

Загрузите:

- весь проект;
- папку `vendor`;
- папку `public/build`.

Не загружайте:

- `.env.example`
- `node_modules`
- `tmp/db`
- локальные docker-файлы, если не нужны на сервере.

### 4. Импортировать базу и выполнить миграции

Если на хостинге нет SSH, миграции обычно запускают:

- через встроенный терминал панели, если он есть;
- через отдельный deployment command;
- либо импортом готовой базы вручную.

Для этого проекта предпочтительно именно выполнить:

```bash
php artisan migrate --force
```

Если такой возможности нет, хостинг не очень подходит для Laravel.

## После выкладки

Проверьте:

- открывается `/`;
- открывается `/admin`;
- работают логин и регистрация;
- грузятся стили и JS из `public/build`;
- запись в `storage` и `bootstrap/cache` разрешена.

Папки, которым обычно нужны права на запись:

- `storage`
- `bootstrap/cache`

## Если хостинг просит `public_html`

Лучший вариант:

- хранить весь Laravel выше `public_html`;
- в `public_html` направить document root на папку `public`.

Если панель хостинга так не умеет, лучше не городить перенос `index.php` вручную без необходимости. Это можно сделать, но такой сценарий лучше настраивать уже под конкретную панель хостинга.
