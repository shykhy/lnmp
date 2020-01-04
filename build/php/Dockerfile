FROM php:7.4-fpm-alpine
RUN echo https://mirrors.aliyun.com/alpine/v3.11/main/ > /etc/apk/repositories \
    && echo https://mirrors.aliyun.com/alpine/v3.11/community/ >> /etc/apk/repositories \
    && apk add autoconf gcc g++ make \
    && wget https://github.com/phpredis/phpredis/archive/5.0.0.tar.gz \
    && tar -zxvf 5.0.0.tar.gz \
    && cd phpredis-5.0.0 \
    && /usr/local/bin/phpize \
    && ./configure --with-php-config=/usr/local/bin/php-config \
    && make \
    && make install \
    && apk add shadow \
    && usermod -u 1000 www-data \
    && groupmod -g 1000 www-data \
# pdo_mysql安装
    && docker-php-ext-install pdo_mysql mysqli pcntl
