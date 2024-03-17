# Try to generate a lightweight package
FROM php:8.2-fpm-alpine

# Needed for pecl install (php extensions)
RUN apk --update add \
      autoconf g++ make \
      openssl-dev \
      libxml2-dev

# https://hub.docker.com/_/php
RUN pecl install redis \
	&& docker-php-ext-enable redis \
    && docker-php-ext-install mysqli pdo pdo_mysql 

# Clean up (https://stackoverflow.com/a/41332978)
RUN apk del autoconf g++ make openssl-dev libxml2-dev 
RUN rm -rf /var/cache/apk/*

# TODO: from php docker docs, does it really work?
# Use the default production configuration (https://hub.docker.com/_/php)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Restore permissions for webserver (changed for node previously)
RUN chown -R www-data:www-data /var/www/html/

