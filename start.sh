#!/bin/bash

echo "Aliasing $FRAMEWORK"
sudo ln -s /etc/nginx/sites/$FRAMEWORK.conf /etc/nginx/sites/enabled.conf

# Starts FPM
nohup /usr/sbin/php-fpm -y /etc/php7/php-fpm.conf -F -O 2>&1 &

# Starts Cron
sudo /usr/sbin/crond -l 8

# Starts notification queue
nohup php /var/www/app/artisan queue:work --verbose --tries=3 --timeout=90 --daemon | sudo tee /var/log/queue.log > /dev/null &

# Starts NGINX!
nginx