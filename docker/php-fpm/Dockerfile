FROM php:7.3-fpm

# install soft
RUN apt-get update && \
    apt-get install -y \
    curl \
    nano \
    cron \
    zip \
    unzip \
    supervisor

# install PHP extensions
RUN docker-php-ext-install pdo_mysql

# install composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# create user and set permissions
RUN usermod -u 1000 www-data

# copy cron configurations
COPY cron /etc/cron.d
RUN chmod 0644 /etc/cron.d/cron
RUN crontab /etc/cron.d/cron

# expose port 9000
EXPOSE 9000

# change directory to app root
WORKDIR /var/www/devicetracker

# setup supervisor
COPY devicetracker.conf /etc/supervisor/conf.d
CMD ["/usr/bin/supervisord"]
