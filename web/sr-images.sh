#!/bin/sh

chmod 777 images
chown -R z:www-data images
chmod -R g+rw images
chmod -R o+rw images
find ./images -type d -print | xargs chmod 777
