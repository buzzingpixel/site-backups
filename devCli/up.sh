#!/usr/bin/env bash

set -e;

touch devCli/dockerfilesHash;
DOCKERFILES_HASH=$(cat docker/app/Dockerfile docker/app-queue-consumer/Dockerfile docker/app-schedule-runner/Dockerfile | sha256sum | awk '{print $1}');
SAVED_HASH=$(cat devCli/dockerfilesHash);

if [[ "$DOCKERFILES_HASH" != "$SAVED_HASH" ]]; then
    chmod +x ./devCli/build.sh;
    ./devCli/build.sh;

    echo "$DOCKERFILES_HASH" > devCli/dockerfilesHash;
fi

touch docker/app/.env.local
touch docker/app/.bash_history

docker compose -f docker/docker-compose.dev.yml -p site-backups up -d;
