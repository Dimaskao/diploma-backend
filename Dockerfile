# FROM composer:2.6 as composer

# FROM nginx:alpine3.18-slim as nginx-base
# FROM nginx-base as nginx-dev
# FROM nginx-base as nginx-app

# FROM php:8.3-fpm-alpine3.19 as fpm-base
# FROM fpm-base as fpm-dev
# FROM fpm-base as fpm-app

# FROM php:8.3-cli-alpine3.19 as cli-base
# FROM cli-base as cli-dev
# FROM cli-base as cli-app

# FROM php:8.3-cli-alpine3.19 as cron-base
# FROM cron-base as cron-dev
# FROM cron-base as cron-app

FROM composer:2.6.6 as composer
COPY composer.json composer.lock ./
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --no-progress \
    --no-dev
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache
COPY . .
RUN composer dump-autoload \
    --classmap-authoritative

# ================  nginx  =================

# 1. Nginx base image
FROM nginx:alpine3.18-slim as nginx-base
ENV CONTAINER_TYPE=base
COPY docker/nginx/conf.d/default.conf /etc/nginx/templates/default.conf.template

# 2. Nginx dev image
FROM nginx-base as nginx-dev
ENV CONTAINER_TYPE=dev

# 3. Nginx app image
FROM nginx-base as nginx-app
ENV CONTAINER_TYPE=app
COPY public/. /app/public

# ================  fpm  =================

# 1. FPM base image
FROM php:8.3-fpm-alpine3.19 as fpm-base
ENV CONTAINER_TYPE=base
WORKDIR /app
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo pdo_mysql exif
RUN install-php-extensions pcntl
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/fpm/fpm.ini $PHP_INI_DIR/conf.d/fpm.ini

# 2. FPM dev image
FROM fpm-base as fpm-dev
ENV CONTAINER_TYPE=dev
RUN apk add --update linux-headers
RUN install-php-extensions xdebug
COPY --from=composer /usr/bin/composer /usr/bin/composer
# ================  cli  =================

# 1. CLI base image
FROM php:8.3-cli-alpine3.19 as cli-base
ENV CONTAINER_TYPE=base
WORKDIR /app
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo pdo_mysql
RUN install-php-extensions pcntl

# 2. CLI dev image
FROM cli-base as cli-dev
ENV CONTAINER_TYPE=dev

# ================  cron  =================

# 1. Cron base image
FROM php:8.3-cli-alpine3.19 as cron-base
ENV CONTAINER_TYPE=base
WORKDIR /app
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo pdo_mysql

# 2. Cron dev image
FROM cron-base as cron-dev
ENV CONTAINER_TYPE=dev
RUN echo "* * * * * cd /app && php artisan schedule:run >> /dev/stdout 2>&1" > /var/spool/cron/crontabs/root
CMD ["crond", "-f"]
