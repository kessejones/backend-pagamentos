#!/bin/bash

# Os serviços devem ser inicializados como root
su - root

echo "Inicializando Supervisor"
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf

echo "Inicializando PHP-FPM"
/usr/local/sbin/php-fpm

