ARG APP_IMAGE=buzzingpixel-site-backups
FROM $APP_IMAGE

CMD printenv | grep -v "no_proxy" >> /etc/environment && /var/www/queueConsumer.sh
