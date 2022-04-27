#!/bin/sh

su app -c "cd /app; php artisan migrate --force"
