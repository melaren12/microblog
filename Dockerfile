FROM php:8.3-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache nginx

COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

EXPOSE 80 9000

CMD ["sh", "-c", "nginx -g 'daemon off;' & php-fpm"]