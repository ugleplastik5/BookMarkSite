#!/bin/sh
set -eu
: "${APP_KEY:?APP_KEY must be configured in Amvera environment variables}"
mkdir -p /data/storage/app/private /data/storage/app/public /data/storage/framework/cache/data /data/storage/framework/sessions /data/storage/framework/views /data/storage/logs
# Keep uploads and runtime files on Amvera's persistent volume.
if [ ! -L /app/storage ]; then
    cp -an /app/storage/. /data/storage/
    rm -rf /app/storage
    ln -s /data/storage /app/storage
fi
php artisan storage:link --force
exec php artisan serve --host=0.0.0.0 --port=3000
