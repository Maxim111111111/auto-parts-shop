#!/usr/bin/env bash
set -euo pipefail

echo "Booting Laravel on Railway..."

APP_KEY_VALUE="${APP_KEY:-}"
if [[ -z "$APP_KEY_VALUE" && -f ".env" ]]; then
  APP_KEY_VALUE="$(grep -E '^APP_KEY=' .env | tail -n 1 | cut -d= -f2- | tr -d '\r' | sed -e 's/^"//' -e 's/"$//')"
fi

if [[ -z "$APP_KEY_VALUE" ]]; then
  echo "ERROR: APP_KEY is not set. Add APP_KEY in Railway Variables and redeploy."
  exit 1
fi

if [[ "${DB_CONNECTION:-sqlite}" == "sqlite" ]]; then
  DB_PATH="${DB_DATABASE:-database/database.sqlite}"
  if [[ "$DB_PATH" != /* ]]; then
    DB_PATH="$(pwd)/${DB_PATH}"
  fi

  mkdir -p "$(dirname "$DB_PATH")"
  touch "$DB_PATH"
fi

php artisan optimize:clear
php artisan config:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
