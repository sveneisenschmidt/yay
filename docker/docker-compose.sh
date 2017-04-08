#! /bin/bash

OS=`uname`
DOCKER_VERSION=`docker version --format '{{.Server.Version}}'`
DOCKER_COMPOSE_FILE_VERSION='v2'

# @See http://ask.xmodulo.com/compare-two-version-numbers.html
function version_gt() { test "$(echo "$@" | tr " " "\n" | sort -V | head -n 1)" != "$1"; }
function version_le() { test "$(echo "$@" | tr " " "\n" | sort -V | head -n 1)" == "$1"; }
function version_lt() { test "$(echo "$@" | tr " " "\n" | sort -rV | head -n 1)" != "$1"; }
function version_ge() { test "$(echo "$@" | tr " " "\n" | sort -rV | head -n 1)" == "$1"; }

if [ "$OS" != "Darwin" ] && version_lt $DOCKER_VERSION 17.04; then
    DOCKER_COMPOSE_FILE_VERSION='v1'
fi

echo "docker/docker-compose.${DOCKER_COMPOSE_FILE_VERSION}.yml"
