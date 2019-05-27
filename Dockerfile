FROM php:7-apache

ENV COMPOSER_HOME=/var/www/composer

# Update and install
RUN apt-get update

RUN apt-get install -y --no-install-recommends libjpeg-dev libpng-dev libzip-dev vim unzip wget libicu-dev
RUN docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr
RUN docker-php-ext-configure intl
RUN docker-php-ext-install gd pdo pdo_mysql intl opcache zip

RUN a2enmod rewrite

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

RUN chown -R 1000.1000 /var/www
RUN chmod ug=rwx+s /var/www

# User settings
RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data

USER 1000
RUN wget https://github.com/PrestaShop/PrestaShop/releases/download/1.6.1.23/prestashop_1.6.1.23.zip -P /var/www/
RUN unzip /var/www/prestashop_1.6.1.23.zip -d /var/www/prestashop
RUN cp -R /var/www/prestashop/prestashop/* /var/www/html
RUN rm -rf /var/www/prestashop
RUN rm /var/www/*.zip

RUN composer require "developersrede/erede-php"
RUN composer require "monolog/monolog"

USER root
