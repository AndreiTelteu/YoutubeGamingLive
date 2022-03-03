#!/bin/sh

su app -c "php artisan migrate --force"
