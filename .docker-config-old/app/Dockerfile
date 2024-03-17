# Multi Stage Build, Composer and npm

# Install composer the easy way
FROM composer:2 as COMPOSER

# Use lightweight image
# https://www.mikestreety.co.uk/blog/docker-image-wth-node-php-and-composer/
FROM php:8.2-cli-alpine as composer_build

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/

# Copy all files to /var/www/html/, except ignored (.dockerignore)
COPY . ./

# Install git, needed for composer
RUN apk add \
	--update \
	--no-cache \
	git 

RUN whoami

# =================== composer build ============================

# TODO: The following might need a re-work, not sure if this is the best way
# Set permissions
RUN chown -R www-data:www-data /var/www/html/
# Enable access for nginx user (if run as non-root)
RUN chmod 755 -R /var/www/html/

# Don't run composer as root
USER www-data

RUN composer install --no-dev --optimize-autoloader --prefer-dist

# =================== npm build ============================

# Use lightweight image
FROM node:20-alpine as npm_build

# https://stackoverflow.com/a/65320366
COPY --from=composer_build /var/www/html /var/www/html

WORKDIR /var/www/html/

RUN ls -lsa

# Set temporary permissions, default (unprivileged) user here is node
RUN chown -R node:node /var/www/html/

# Run as unprivileged user (security)
USER node

RUN ls -lsa

# Build
RUN npm install
RUN NODE_ENV=production npm run build

# Clean up
RUN rm -rf /var/www/html/node_modules
