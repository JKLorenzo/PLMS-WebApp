#!/bin/bash

sleep 10
php artisan config:cache
php artisan migrate --force
php artisan optimize
php artisan queue:work