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

PORT_VALUE="${PORT:-10000}"

if [[ "$PORT_VALUE" =~ :([0-9]{2,5})$ ]]; then
  PORT_VALUE="${BASH_REMATCH[1]}"
fi

if ! [[ "$PORT_VALUE" =~ ^[0-9]+$ ]] || ((PORT_VALUE < 1 || PORT_VALUE > 65535)); then
  echo "WARN: Invalid PORT value '${PORT:-}'. Falling back to 10000."
  PORT_VALUE="10000"
fi

echo "Starting Laravel on 0.0.0.0:${PORT_VALUE}"
exec php artisan serve --host=0.0.0.0 --port="${PORT_VALUE}"
