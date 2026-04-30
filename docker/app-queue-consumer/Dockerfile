ARG APP_IMAGE=ghcr.io/buzzingpixel/site-backups-app
FROM $APP_IMAGE

CMD printenv | grep -v "no_proxy" >> /etc/environment && /var/www/queueConsumer.sh
