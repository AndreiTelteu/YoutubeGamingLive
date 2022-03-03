#!/bin/sh
cd /app;

# dev
su app -c "/usr/bin/composer install --no-interaction"

# prod
# /usr/bin/composer install \
#     --no-ansi \
#     --no-dev \
#     --no-interaction \
#     --no-plugins \
#     --no-progress \
#     --no-scripts \
#     --optimize-autoloader;
