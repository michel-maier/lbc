FROM php:8.0-cli-alpine3.14

ENV COMPOSER_HOME=/.composer
ENV PATH="$PATH:$COMPOSER_HOME/vendor/bin"

RUN mkdir $COMPOSER_HOME && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS util-linux-dev postgresql-dev && \
    apk add --no-cache util-linux postgresql && \
    pecl install xdebug uuid && \
    docker-php-ext-enable xdebug uuid && \
    docker-php-ext-install -j"$(nproc)" pdo_pgsql && \
    apk del .build-deps && \
    rm -r /tmp/pear/cache/* /tmp/pear/download/* && \
    curl --silent --fail-early https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer global require symfony/phpunit-bridge phpspec/prophecy-phpunit && \
    chmod -R o+rwx $COMPOSER_HOME

WORKDIR /app