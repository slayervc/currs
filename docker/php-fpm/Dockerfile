FROM php:7.4-fpm

RUN apt-get update \
&& apt-get install -y \
   libpq-dev \
   libzip-dev \
   zip \
&& docker-php-ext-install zip pdo_pgsql \
&& pecl install xdebug \
&& docker-php-ext-enable xdebug

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php composer-setup.php \
&& php -r "unlink('composer-setup.php');" \
&& mv composer.phar /usr/local/bin/composer

WORKDIR /www