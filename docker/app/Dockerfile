FROM php:8.4.20
ARG TARGETARCH

WORKDIR /var/www

RUN apt-get update && apt-get install -y --no-install-recommends \
    gnupg \
    zip \
    unzip \
    libonig-dev \
    libssl-dev \
    libzip-dev \
    openssl \
    libcurl4 \
    libcurl4-openssl-dev \
    libxpm-dev \
    openssh-client \
    rsync \
    vim \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mbstring \
    && docker-php-ext-install opcache \
    && docker-php-ext-install zip \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis

RUN pecl install ssh2 && docker-php-ext-enable ssh2

COPY --from=composer:2.9.5 /usr/bin/composer /usr/bin/composer

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/app/php.ini $PHP_INI_DIR/conf.d/

ENV COMPOSER_ALLOW_SUPERUSER=1

# Update bash profile
COPY ./docker/.profile /root/.profile
COPY ./docker/.profile /root/.bashrc

COPY . /var/www

RUN composer install --no-interaction --no-ansi --no-progress --optimize-autoloader --no-dev
