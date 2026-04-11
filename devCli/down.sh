#!/usr/bin/env bash

set -e;

docker compose -f docker/docker-compose.dev.yml -p site-backups down;
