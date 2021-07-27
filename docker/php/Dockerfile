FROM php:8.0-cli-alpine3.14

ENV COMPOSER_HOME=/.composer
ENV PATH="$PATH:$COMPOSER_HOME/vendor/bin"

RUN mkdir $COMPOSER_HOME && \
    apk add --no-cache $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apk del $PHPIZE_DEPS && \
    rm -r /tmp/pear/cache/* /tmp/pear/download/* && \
    curl --silent --fail-early https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer global require symfony/phpunit-bridge && \
    chmod -R o+rwx $COMPOSER_HOME

WORKDIR /app