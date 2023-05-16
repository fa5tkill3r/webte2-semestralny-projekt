#!/bin/bash

if [ ! -f /var/www/html/public/migration.temp ]; then
    php artisan migrate
    touch /var/www/html/public/migration.temp
fi
