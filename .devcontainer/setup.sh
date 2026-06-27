#!/bin/sh
set -e

apk add --no-cache bash git nodejs npm

npm install -g @fission-ai/openspec

if [ -f package.json ]; then
    npm install
fi

# docker-php-ext-install dom simplexml xml xmlwriter

curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

composer install