#!/usr/bin/env bash

set -e;

docker build \
    --build-arg BUILDKIT_INLINE_CACHE=1 \
    --cache-from buzzingpixel-site-backups \
    --file "docker/app/Dockerfile" \
    --tag buzzingpixel-site-backups \
    .;

docker build \
    --build-arg BUILDKIT_INLINE_CACHE=1 \
    --cache-from buzzingpixel-site-backups-queue-consumer \
    --file "docker/app-queue-consumer/Dockerfile" \
    --tag buzzingpixel-site-backups-queue-consumer \
    .;

docker build \
    --build-arg BUILDKIT_INLINE_CACHE=1 \
    --cache-from buzzingpixel-site-backups-schedule-runner \
    --file "docker/app-schedule-runner/Dockerfile" \
    --tag buzzingpixel-site-backups-schedule-runner \
    .;
