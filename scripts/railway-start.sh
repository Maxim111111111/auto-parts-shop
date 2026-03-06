#!/usr/bin/env bash
set -euo pipefail

echo "Booting Laravel on Railway..."

APP_KEY_VALUE="${APP_KEY:-}"
if [[ -z "$APP_KEY_VALUE" && -f ".env" ]]; then
  APP_KEY_VALUE="$(grep -E '^APP_KEY=' .env | tail -n 1 | cut -d= -f2- | tr -d '\r' | sed -e 's/^"//' -e 's/"$//' || true)"
fi

if [[ -z "$APP_KEY_VALUE" ]]; then
  APP_KEY_VALUE="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
  export APP_KEY="$APP_KEY_VALUE"
  echo "WARN: APP_KEY is not set. Generated a temporary key for this boot."
  echo "WARN: Set APP_KEY in Railway Variables to keep sessions/cookies stable across restarts."
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

echo "Starting HTTP server on 0.0.0.0:${PORT:-8080}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
