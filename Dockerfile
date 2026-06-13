FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       git unzip ca-certificates libpq-dev libicu-dev libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql intl mbstring zip opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json ./
RUN composer install --no-interaction --prefer-dist --no-progress --no-dev --optimize-autoloader

COPY . .
COPY render-start.sh /usr/local/bin/render-start.sh
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir -p runtime web/assets \
    && chown -R www-data:www-data runtime web/assets \
    && chmod -R 775 runtime web/assets \
    && chmod +x /usr/local/bin/render-start.sh

EXPOSE 80

CMD ["render-start.sh"]
