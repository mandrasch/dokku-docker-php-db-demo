# Install composer the easy way
FROM composer:2 as COMPOSER

# Combined nginx + php-fpm
# Fork of https://github.com/bkuhl/fpm-nginx
# TODO: use ARG to set php and nodejs version
# TODO: Bundle this into separate base image?
# TODO: Or use serversideup/php:8.2-fpm-nginx?
FROM php:8.2-fpm-alpine3.18 as base

# Install composer the easy way (see above)
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set working directory inside docker container
WORKDIR /var/www/html

# Copy the host computer files to the container (except .dockerignore's)
ADD --chown=www-data:www-data  . /var/www/html

RUN apk update 

# Install nginx and s6 supervisor (because we have two processes, nginx + php-fpm)
RUN apk add nginx s6-overlay

# ------------------------ Common PHP Dependencies -------------------------------
# TODO: this part is currently slow, especially opcode --> use custom base image to just pull it
RUN apk add \
        # needed for composer
        git  \
        # see https://github.com/docker-library/php/issues/880
        oniguruma-dev \
        # needed for gd
        libpng-dev libjpeg-turbo-dev \
        # clean up
        && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
        # Installing common deps for php
        && docker-php-ext-install mbstring pdo_mysql gd opcache \
        # TODO: why is that needed?
        && mkdir -p /home/www-data/.composer/cache \
        # set permissions
        && chown -R www-data:www-data /home/www-data/ /var/www/html 


# ----------------------- Composer install / build -------------------------------

# Don't run composer as root
USER www-data

# Run composer build for production
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# TODO: Clean up, remove composer for smaller dist size?
USER root
RUN apk del git 

# ----------------------- NodeJS install / build -------------------------------

# TODO: Use ARGS to specify alpine version
FROM node:20-alpine as nodejs_build

# TODO: is the image smaller if we only copy needed files?
# Copy package.json & Co
COPY --from=base /var/www/html /var/www/html

WORKDIR /var/www/html/

RUN ls -lsa

# Set temporary permissions, default (unprivileged) user here is node
RUN chown -R node:node /var/www/html/
# Run as unprivileged user (security)
USER node
RUN npm install
RUN NODE_ENV=production npm run build

# Clean up
RUN rm -rf /var/www/html/node_modules

# --------------------------- switch back ----------------------------------------

# Switch back to the PHP image for starting FPM/Nginx + copy files
FROM base

WORKDIR /var/www/html

USER root

COPY --from=nodejs_build /var/www/html/public/dist /var/www/html/public/dist
# need to be explicit with .vite (hidden) directory
COPY --from=nodejs_build /var/www/html/public/dist/.vite /var/www/html/public/dist/.vite

# Clean uo
RUN rm -rf /var/www/html/.docker-config

# Set all permissions (otherwise we had permission denied)
RUN chown -R www-data:www-data /var/www/html/

# ------------------------ start fpm/nginx ---------------------------------------

RUN whoami

COPY .docker-config/webserver/services.d /etc/services.d

# Fix: permission denied error for s6-overlay, let maintainer of nginx-fpm know?
RUN chmod +x /etc/services.d/nginx/run
RUN chmod +x /etc/services.d/php-fpm/run

COPY .docker-config/webserver/nginx.conf /etc/nginx/nginx.conf

# TODO: why is that done?
# Adding the opcache configuration into the wrong directory intentionally.
# This is enabled at runtime
ADD .docker-config/webserver/opcache.ini /usr/local/etc/php/opcache_disabled.ini
ADD .docker-config/webserver/healthcheck.ini /usr/local/etc/php/healthcheck.ini

RUN ls -lsa /etc/services.d

# Clean up for smaller image size
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

EXPOSE 80

# starts s6-overlay supervisor (https://github.com/just-containers/s6-overlay)
ENTRYPOINT ["/init"]
CMD []