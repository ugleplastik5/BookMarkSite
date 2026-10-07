#!/bin/sh
set -eu

APP_ROOT=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
cd "$APP_ROOT"
: "${APP_KEY:?APP_KEY must be configured in Amvera environment variables}"
STORAGE_ROOT=${STORAGE_ROOT:-/data/storage}
mkdir -p "$STORAGE_ROOT/app/private" "$STORAGE_ROOT/app/public" \
    "$STORAGE_ROOT/framework/cache/data" "$STORAGE_ROOT/framework/sessions" \
    "$STORAGE_ROOT/framework/views" "$STORAGE_ROOT/logs" bootstrap/cache

# Copy defaults only from a real directory. Never rely on a preview symlink.
if [ ! -L "$APP_ROOT/storage" ] && [ -d "$APP_ROOT/storage" ]; then
    cp -an "$APP_ROOT/storage/." "$STORAGE_ROOT/"
fi
# Repair stale symlinks as well as first-time deployments.
if [ "$APP_ROOT/storage" != "$STORAGE_ROOT" ]; then
    if [ -L "$APP_ROOT/storage" ]; then
        rm "$APP_ROOT/storage"
    elif [ -d "$APP_ROOT/storage" ]; then
        rm -rf "$APP_ROOT/storage"
    fi
    ln -s "$STORAGE_ROOT" "$APP_ROOT/storage"
fi

# Rebuild configuration from runtime environment, not build-time settings.
php artisan config:clear
php artisan storage:link --force
printf 'Starting BookMark on 0.0.0.0:3000\n'
exec php artisan serve --host=0.0.0.0 --port=3000 --no-reload
