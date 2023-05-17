#!/bin/bash

until mysql -h"mysql" -u"webte" -p"webte" -e "quit" >/dev/null 2>&1; do
    echo "Waiting for MySQL to be available..."
    sleep 1
done

if [ ! -f /var/www/html/public/migration.temp ]; then
    php artisan migrate --force
    touch /var/www/html/public/migration.temp
fi
