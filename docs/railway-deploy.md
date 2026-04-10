# Railway Deploy

Проект подготовлен под текущий Railway Railpack без `Procfile` и без ручного `php artisan serve`.

## Что уже настроено в репозитории

- Laravel будет определяться Railway автоматически.
- Document root для Laravel Railway выставляет на `public`.
- В [bootstrap/app.php](/Users/pc/Documents/laravel_projects/auto-parts-shop/bootstrap/app.php) включено доверие proxy-заголовкам, чтобы корректно работали `https`, cookies и URL за прокси Railway.
- В [database/seeders/DatabaseSeeder.php](/Users/pc/Documents/laravel_projects/auto-parts-shop/database/seeders/DatabaseSeeder.php) демо-данные по умолчанию отключены в production.
- В [railway.json](/Users/pc/Documents/laravel_projects/auto-parts-shop/railway.json) задан `RAILPACK` и healthcheck на `/up`.
- В [.dockerignore](/Users/pc/Documents/laravel_projects/auto-parts-shop/.dockerignore) исключены локальные артефакты, включая `tmp/db`.

## Что создать в Railway

1. Новый проект.
2. Сервис приложения из GitHub-репозитория.
3. Отдельный сервис `MySQL`.
4. Для приложения включить `Public Networking` и сгенерировать домен.

## Переменные приложения

В сервис приложения открой `Variables` и через `Raw Editor` вставь содержимое из [.env.railway.example](/Users/pc/Documents/laravel_projects/auto-parts-shop/.env.railway.example), затем замени:

- `APP_KEY` на реальный ключ.
- При необходимости `MAIL_FROM_ADDRESS`.

### Как получить `APP_KEY`

Локально в проекте:

```bash
php artisan key:generate --show
```

Полученное значение вставь в `APP_KEY` в Railway.

### Что важно по базе

- `DB_URL=${{MySQL.MYSQL_URL}}` должен ссылаться именно на сервис Railway MySQL.
- Отдельно `DB_HOST`, `DB_PORT`, `DB_DATABASE` и другие параметры не нужны, потому что Laravel уже читает `DB_URL`.

## Как деплоить

1. Запушь текущие изменения в GitHub.
2. В Railway выбери `Deploy from GitHub Repo`.
3. Подключи репозиторий.
4. Добавь сервис `MySQL`.
5. В приложении вставь переменные.
6. Нажми `Deploy`.

## Что должно произойти на деплое

Railway сам:

- определит Laravel;
- установит `composer`-зависимости;
- установит `npm`-зависимости, так как в проекте есть `package.json`;
- выполнит frontend build;
- поднимет приложение через Railpack.

## Проверка после деплоя

Проверь:

- `/up`
- `/`
- `/admin`

Если `/up` отвечает, но стили не грузятся, значит нужно смотреть build logs Vite.

## Типовые причины падения

- Не задан `APP_KEY`.
- Не создан публичный домен, а `APP_URL` уже ожидает `RAILWAY_PUBLIC_DOMAIN`.
- Переменная `DB_URL` ссылается не на тот сервис.
- В Railway остались старые переменные от `sqlite`.

## Если нужны демо-данные

Для разовой загрузки демо-данных можно временно поставить:

```env
SEED_DEMO_DATA=true
```

после чего перезапустить deploy. Затем лучше вернуть `false`.
