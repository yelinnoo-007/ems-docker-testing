FROM php:8.2.0-apache
WORKDIR /var/www/html
# COPY nginx/default.conf /etc/nginx/conf.d
# COPY ./event-management-system/ /var/www/html/
RUN docker-php-ext-install pdo pdo_mysql 

#Mod Rewrite
RUN a2enmod rewrite