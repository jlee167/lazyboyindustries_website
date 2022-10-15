#!/bin/bash

#DEST=/var/www/lazyweb
#DEST2=/var/www/html
#SRC=./client/*

DEST=/var/www/lazyweb
SRC=./*

#composer update
composer clearcache
composer install
npm install --loglevel=error
npm run dev --loglevel=error
#npm run production --loglevel=error

#Save existing profile images
sudo cp -r -u ${DEST}/public/images/* ./public/images

sudo rm -r ${DEST}/*
sudo cp -r ${SRC} ${DEST}
sudo cp ~/.env ${DEST}
sudo cp ~/.env ./



#sudo cp -r -f ./vendor ${DEST}
#sudo cp -r -f ./streams ${DEST}
#sudo cp -r -f ${SRC} ${DEST2}
#sudo cp -r -f ./vendor ${DEST2}
#sudo cp -r -f ./streams ${DEST2}

sudo chown -R www-data:www-data ${DEST}
sudo chmod -R 775 ${DEST}/storage
sudo chmod -R 775 ${DEST}/bootstrap/cache

sudo a2enmod rewrite
sudo systemctl reload apache2
