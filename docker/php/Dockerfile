FROM php:8.0-cli-alpine3.14

ENV COMPOSER_DIR=/.composer

RUN mkdir $COMPOSER_DIR && chmod -R o+rwx $COMPOSER_DIR && \
    apk add --no-cache $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apk del $PHPIZE_DEPS && \
    rm -r /tmp/pear/cache/* /tmp/pear/download/* && \
    curl --silent --fail-early https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app