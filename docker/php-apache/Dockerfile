FROM php:8.1-apache

RUN apt-get update

RUN apt-get install zip -y

RUN apt-get update && apt-get install -y \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd

RUN a2enmod rewrite

RUN ["php","-r copy('https://getcomposer.org/installer', 'composer-setup.php');"]
RUN ["php","composer-setup.php"]
RUN mv ./composer.phar /usr/local/bin/composer

RUN docker-php-ext-install pdo_mysql

EXPOSE 80