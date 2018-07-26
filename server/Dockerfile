FROM richarvey/nginx-php-fpm

COPY src /var/www/html/
COPY vendor /var/www/vendor/

RUN sh -c 'echo "extension=redis.so">/usr/local/etc/php/conf.d/redis.ini'
RUN cp /var/www/vendor/phpredis/modules/redis.so /usr/local/lib/php/extensions/no-debug-non-zts-20170718



