FROM php:7-alpine

WORKDIR "/var/www"

# Install updating and upgrading
RUN apk --no-cache update \
    && apk --no-cache upgrade

#Dependencies for postgre install
RUN apk add --no-cache  libpq postgresql-dev $PHPIZE_DEPS openssl-dev

#Installing connection libraries and xdebug
RUN docker-php-ext-install opcache
RUN docker-php-ext-enable  opcache

RUN docker-php-ext-install pdo
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pgsql
RUN docker-php-ext-install pdo_pgsql 



# XDebug
RUN pecl install xdebug-2.9.6
RUN docker-php-ext-enable xdebug

RUN sed -i '1 a xdebug.remote_handler=dbgp' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_connect_back=1' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_port=9000' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_enable=1' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_autostart=1' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#Composer
RUN apk add git
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
    && mkdir -p /usr/local/bin \
    && php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('/tmp/composer-setup.php');"

COPY composer.json /var/www/composer.json

RUN composer install --no-dev

#ENV TERM xterm

#EXPOSE 80 11211

#ENTRYPOINT ["apachectl", "-DFOREGROUND"]
