#!/usr/bin/env bash

set -e;

docker run -it --rm \
    --env-file docker/app/.env \
    --env-file docker/app/.env.local \
    -v $(pwd):/var/www \
    -v /Users/tj/.ssh/id_rsa:/root/.ssh/id_rsa \
    -v /Users/tj/.ssh/id_rsa.pub:/root/.ssh/id_rsa.pub \
    -v $(pwd)/storage:/storage \
    buzzingpixel-site-backups \
    bash;
