#!/bin/sh
set -eu

APP_ROOT=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
cd "$APP_ROOT"
: "${APP_KEY:?APP_KEY must be configured in Amvera environment variables}"
# Keep exceptions in platform logs; never expose debug pages to visitors.
export LOG_STACK=${LOG_STACK:-single,stderr}
STORAGE_ROOT=${STORAGE_ROOT:-/data/storage}
mkdir -p "$STORAGE_ROOT/app/private" "$STORAGE_ROOT/app/public" \
    "$STORAGE_ROOT/framework/cache/data" "$STORAGE_ROOT/framework/sessions" \
    "$STORAGE_ROOT/framework/views" "$STORAGE_ROOT/logs" bootstrap/cache

if [ ! -L "$APP_ROOT/storage" ] && [ -d "$APP_ROOT/storage" ]; then
    cp -an "$APP_ROOT/storage/." "$STORAGE_ROOT/"
fi
if [ "$APP_ROOT/storage" != "$STORAGE_ROOT" ]; then
    if [ -L "$APP_ROOT/storage" ]; then
        rm "$APP_ROOT/storage"
    elif [ -d "$APP_ROOT/storage" ]; then
        rm -rf "$APP_ROOT/storage"
    fi
    ln -s "$STORAGE_ROOT" "$APP_ROOT/storage"
fi

php artisan config:clear
php artisan view:clear
php artisan storage:link --force
# Validate the existing key without generating a new one or invalidating data.
php deploy/check-runtime.php
# Apply only pending forward migrations. Never reset, roll back or seed the DB.
if [ "${MIGRATE_ON_START:-true}" = "true" ]; then
    php artisan migrate --force --no-interaction
fi
printf 'Starting BookMark on 0.0.0.0:3000\n'
exec php artisan serve --host=0.0.0.0 --port=3000 --no-reload
