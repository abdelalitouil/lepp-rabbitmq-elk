FROM alpine:3.10

RUN apk add --update --no-cache \
    coreutils \
    php7-fpm \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-json \
    php7-intl \
    php7-mcrypt \
    php7-fileinfo\
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_pgsql\
    php7-xml \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-xdebug \
    php7-zip \
    php7-xmlwriter \
    php7-sockets\
    php7-bcmath\
    php7-pecl-amqp\
    make \
    curl\
    composer\
    supervisor

COPY symfony.ini /etc/php7/conf.d/
COPY symfony.ini /etc/php7/cli/conf.d/
COPY symfony.pool.conf /etc/php7/php-fpm.d/
COPY xdebug.ini  /etc/php7/conf.d/
COPY supervisord.conf  /etc/

CMD /usr/bin/supervisord -c /etc/supervisord.conf ; php-fpm7 -F

WORKDIR /var/www/symfony
EXPOSE 9001