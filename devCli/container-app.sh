#!/usr/bin/env bash

set -e;

#docker run -it --rm \
#    --name buzzingpixel-site-backups-app \
#    --env-file docker/app/.env \
#    --env-file docker/app/.env.local \
#    -v $(pwd):/var/www \
#    -v /Users/tj/.ssh/id_rsa:/root/.ssh/id_rsa \
#    -v /Users/tj/.ssh/id_rsa.pub:/root/.ssh/id_rsa.pub \
#    -v $(pwd)/storage:/storage \
#    buzzingpixel-site-backups \
#    bash;

docker exec -it buzzingpixel-site-backups-schedule-runner bash;
