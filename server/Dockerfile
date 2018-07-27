FROM richarvey/nginx-php-fpm

COPY src /var/www/html/

RUN apk add autoconf && apk add g++ && apk add make && \
    cd /var/www && \
    mkdir vendor && \
    cd vendor && \
    git clone git://github.com/nicolasff/phpredis.git && \
    cd phpredis && \
    phpize && \
    ./configure && \
    make && make install

RUN sh -c 'echo "extension=redis.so">/usr/local/etc/php/conf.d/redis.ini'
RUN cp /var/www/vendor/phpredis/modules/redis.so /usr/local/lib/php/extensions/no-debug-non-zts-20170718



