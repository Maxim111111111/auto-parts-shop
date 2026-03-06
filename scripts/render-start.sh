#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

if [[ -z "${APP_KEY:-}" ]]; then
  echo "ERROR: APP_KEY is not set. Set APP_KEY in Render environment variables."
  exit 1
fi

php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

if [[ "${RUN_MIGRATIONS:-true}" == "true" ]]; then
  php artisan migrate --force
fi

echo "Starting Laravel on 0.0.0.0:${PORT:-10000}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"

