#!/bin/sh
cd /app;

# dev
su app -c "/usr/bin/composer install"

# prod
# /usr/bin/composer install \
#     --no-ansi \
#     --no-dev \
#     --no-interaction \
#     --no-plugins \
#     --no-progress \
#     --no-scripts \
#     --optimize-autoloader;
