FROM php:7.3-apache

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
RUN wget https://github.com/PrestaShop/PrestaShop/releases/download/1.7.6.9/prestashop_1.7.6.9.zip -P /var/www/
RUN unzip /var/www/prestashop_1.7.6.9.zip -d /var/www/prestashop/
RUN cp /var/www/prestashop/index.php /var/www/html
RUN cp /var/www/prestashop/prestashop.zip /var/www/html
RUN mkdir /var/www/html/modules
RUN rm -rf /var/www/prestashop
RUN rm /var/www/*.zip

RUN composer require "developersrede/erede-php"
RUN composer require "monolog/monolog"

USER root
