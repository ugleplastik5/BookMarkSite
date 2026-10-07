#!/bin/sh
set -eu

APP_ROOT=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
cd "$APP_ROOT"
: "${APP_KEY:?APP_KEY must be configured in Amvera environment variables}"
STORAGE_ROOT=${STORAGE_ROOT:-/data/storage}
mkdir -p "$STORAGE_ROOT/app/private" "$STORAGE_ROOT/app/public" \
    "$STORAGE_ROOT/framework/cache/data" "$STORAGE_ROOT/framework/sessions" \
    "$STORAGE_ROOT/framework/views" "$STORAGE_ROOT/logs" bootstrap/cache

# Preserve uploads and runtime files across Amvera deployments.
if [ ! -L "$APP_ROOT/storage" ]; then
    cp -an "$APP_ROOT/storage/." "$STORAGE_ROOT/"
    rm -rf "$APP_ROOT/storage"
    ln -s "$STORAGE_ROOT" "$APP_ROOT/storage"
fi

# Rebuild configuration from the runtime environment, not build-time settings.
php artisan config:clear
php artisan storage:link --force
exec php artisan serve --host=0.0.0.0 --port=3000 --no-reload
