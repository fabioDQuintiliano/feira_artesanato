#!/bin/sh
set -eu

cd /var/www/html

if [ ! -f vendor/autoload.php ]; then
    composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --prefer-dist \
        --optimize-autoloader
fi

for directory in \
    arquivos \
    images/upload \
    tables \
    admin/exe_system \
    containers/exe_system
do
    mkdir -p "$directory"
    chown -R www-data:www-data "$directory" 2>/dev/null || true
    chmod -R ug+rwX "$directory" 2>/dev/null || true
done

touch functions/__list_functions.php
chown www-data:www-data functions/__list_functions.php 2>/dev/null || true
chmod 666 functions/__list_functions.php 2>/dev/null || true

exec "$@"
