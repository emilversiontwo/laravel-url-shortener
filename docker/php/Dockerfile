FROM php:8.4-fpm

ARG UID=1000
ARG GID=1000

RUN set -eux; \
    apt-get update && apt-get install -y --no-install-recommends \
      ca-certificates apt-transport-https gnupg2 zip unzip git \
      libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
      libonig-dev libxml2-dev curl build-essential pkg-config libicu-dev \
      zlib1g-dev \
    ; \
    mkdir -p /tmp/pear/cache && chown -R www-data:www-data /tmp/pear; \
    pecl config-set temp_dir /tmp/pear || true; \
    pecl install --onlyreqdeps --force redis \
    docker-php-ext-configure gd --with-jpeg --with-freetype; \
    docker-php-ext-install -j"$(nproc)" \
        gd pdo pdo_pgsql pgsql zip bcmath intl sockets opcache exif \
        mbstring xml fileinfo pcntl \
    ; \
    docker-php-ext-enable redis.so \
    pecl clear-cache || true; \
    rm -rf /var/lib/apt/lists/* /tmp/pear/cache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN groupadd -g ${GID} appuser || true \
    && useradd -u ${UID} -g ${GID} -m -s /bin/bash appuser || true \
    && mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache /tmp \
    && chown -R appuser:appuser /var/www/html /tmp \
    && chmod -R 775 /var/www/html \
    && chmod 1777 /tmp

RUN sed -i 's/user = www-data/user = appuser/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/group = www-data/group = appuser/g' /usr/local/etc/php-fpm.d/www.conf

COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini

RUN git config --global --add safe.directory /var/www/html

ENV COMPOSER_HOME=/composer

EXPOSE 9000
CMD ["php-fpm"]
