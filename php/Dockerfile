FROM php:7.2.7-fpm-alpine3.7
RUN docker-php-ext-install mysqli
RUN apk add libzip-dev && docker-php-ext-install zip