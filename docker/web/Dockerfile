FROM php:7.4-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf; \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf; \
    a2enmod rewrite; \
    \
    curl -o /usr/local/bin/install-php-extensions \
    -fL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions; \
    chmod +x /usr/local/bin/install-php-extensions; \
    install-php-extensions @composer-2.1.9 pdo_mysql xdebug; \
    { \
        printf '%s\n' \
        'error_reporting = E_ALL' \
        'display_startup_errors = On' \
        'log_errors = On'; \
    } > /usr/local/etc/php/conf.d/000-php.ini; \
    { \
        printf '%s\n' \
        'xdebug.client_host = host.docker.internal' \
        'xdebug.mode = develop,debug' \
        'xdebug.start_with_request = yes' \
        'xdebug.log = /tmp/xdebug.log'; \
    } > /usr/local/etc/php/conf.d/000-xdebug.ini; \
    \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        zip \
        unzip \
        git; \
    rm -rf /var/lib/apt/lists/*
