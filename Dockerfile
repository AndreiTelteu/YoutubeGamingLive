FROM phpdockerio/php:8.1-fpm
WORKDIR "/app"

ENV TERM=linux
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update; \
    apt-get -y --no-install-recommends install software-properties-common wget git nano; \
    wget -O- https://nginx.org/keys/nginx_signing.key | apt-key add - ; \
    echo "deb https://nginx.org/packages/mainline/ubuntu/ focal nginx" > /etc/apt/sources.list.d/nginx.list ; \
    add-apt-repository ppa:openswoole/ppa -y; \
    apt-get -y --no-install-recommends install \
        nginx \
        libfcgi-bin \
        php8.1-mysql \
        php8.1-redis \
        php8.1-openswoole \
        mysql-client; \
    systemctl enable nginx; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN groupadd -r app -g 1000 && useradd -u 1000 -r -g app -m -d /home/app -s /usr/bin/bash -c "App user" app
RUN wget -O /usr/local/bin/php-fpm-healthcheck \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

COPY . .
RUN cp ./docker/php/php.ini /etc/php/8.1/fpm/conf.d/99-overrides.ini
RUN cp ./docker/php/pool.conf /etc/php/8.1/fpm/pool.d/z-overrides.conf
RUN cp ./docker/php/docker-entrypoint.sh /docker-entrypoint.sh
RUN mkdir /docker-entrypoint.d/
RUN cp ./docker/php/entrypoint/11-migrate.sh /docker-entrypoint.d/11-migrate.sh
RUN cp ./docker/php/bash_aliases /home/app/.bash_aliases
RUN cp ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
RUN cp ./docker/nginx/vhost-prod.conf /etc/nginx/conf.d/default.conf
RUN chown -R app:app .
RUN chown -R app:app /home/app/.bash_aliases

RUN echo '#!/bin/sh\nservice nginx start' > /docker-entrypoint.d/99-start-nginx.sh
RUN chmod +x /docker-entrypoint.d/99-start-nginx.sh

RUN su app -c "/usr/bin/composer install \
    --no-ansi \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-progress \
    --no-scripts \
    --optimize-autoloader";

ENTRYPOINT [ "/docker-entrypoint.sh" ]
CMD [ "/usr/sbin/php-fpm8.1", "-O" ]
