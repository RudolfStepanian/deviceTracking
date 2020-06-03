#!/bin/bash

# build images
docker-compose build

# run containers
docker-compose up -d

# install packages
docker-compose exec app composer install

# set permissions for vendor folder
docker-compose exec app chown -R www-data:1000 vendor
docker-compose exec app chown -R www-data:1000 vendor

# generate app key
docker-compose exec app php artisan key:generate

# clear and cache configurations
docker-compose exec app php artisan config:cache

# set permissions on nginx container
docker-compose exec nginx chmod 775 /var/www/devicetracker/storage
docker-compose exec nginx chmod 775 /var/www/devicetracker/bootstrap/cache

# run migrations/seeders
docker-compose exec app php artisan migrate --seed
