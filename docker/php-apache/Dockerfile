FROM php:8.1-apache

RUN apt update

RUN apt install zip -y

RUN a2enmod rewrite

RUN ["php","-r copy('https://getcomposer.org/installer', 'composer-setup.php');"]
RUN ["php","composer-setup.php"]
RUN mv ./composer.phar ./composer

RUN docker-php-ext-install pdo_mysql

EXPOSE 80