#!/usr/bin/env bash

set -e;

docker build \
    --build-arg BUILDKIT_INLINE_CACHE=1 \
    --cache-from ghcr.io/buzzingpixel/site-backups-app \
    --file "docker/app/Dockerfile" \
    --tag ghcr.io/buzzingpixel/site-backups-app \
    .;

docker build \
    --build-arg BUILDKIT_INLINE_CACHE=1 \
    --cache-from ghcr.io/buzzingpixel/site-backups-queue-consumer \
    --file "docker/app-queue-consumer/Dockerfile" \
    --tag ghcr.io/buzzingpixel/site-backups-queue-consumer \
    .;

docker build \
    --build-arg BUILDKIT_INLINE_CACHE=1 \
    --cache-from ghcr.io/buzzingpixel/site-backups-schedule-runner \
    --file "docker/app-schedule-runner/Dockerfile" \
    --tag ghcr.io/buzzingpixel/site-backups-schedule-runner \
    .;
