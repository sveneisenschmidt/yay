
MAKEFLAGS += --warn-undefined-variables
OS := $(shell uname)
SHELL := bash
.SHELLFLAGS := -eu -o pipefail -c
.DEFAULT_GOAL := all
.DELETE_ON_ERROR:
.SUFFIXES:
.PHONY: .preflight-check

# Variables
BASH := bash
ECHO := echo
SLEEP := sleep
TEST := test
SED := sed
CUT := cut
CHMOD := chmod
FIND := find
TAIL := tail
CP := cp
MKDIR := mkdir
RM := rm
MAKE := make
TOUCH := touch
DOCKER := docker
GREP := grep
XARGS := xargs
PROJECT := yay
COMPOSE_FILE := $(shell bash docker/docker-compose.sh)
COMPOSE := docker-compose -p $(PROJECT)
CONTAINER_PWD := $(shell pwd)
CONTAINER_USER := $(shell id -u)

# macOS compability
ifeq ($(OS),Darwin)
GREP := ggrep
XARGS := gxargs
SED := gsed
endif
REQ_BREW_TAPS = "homebrew/dupes"
REQ_BREW_PACKAGES = "coreutils gnu-sed grep wget findutils"

# Make dependencies
REQ_DEPS = ${GREP} ${XARGS} ${SED}

all: \
	.all-project \
	.all-help \
	.all-preflight-check

.all-project:
	# Project:
	#  yay
	#

.all-help:
	# Targets:
	#   start                     Start the containers
	#   stop                      Stop the containers
	#
	#   install                   Install all dependencies for development
	#   build-assets              Builds static assets
	#   watch-assets              Watch assets and continuously build them
	#   watch-logs                Watch log files
	#   watch-redis               Monitor redis queries
	#
	#   clean                     Clean all build outputs
	#   clean-logs                Clean all log files
	#   clean-caches              Clean and restart the Redis database and clear the application cache
	#
	#   test                      Run the test suite
	#   shell                     Start a interactive shell session on the container
	#

.all-preflight-check:
	# Installed binaries:
	@$(foreach REQ_DEP,$(REQ_DEPS), command -v $(REQ_DEP) >/dev/null 2>&1 \
		&& echo "#   ✓ $(REQ_DEP) (installed)" || echo "#   × $(REQ_DEP) (missing)"; )
	#
	# Howto install binaries for OSX/macOS:
	@if [[ "${OS}" -eq "Darwin"  ]] ; then echo "#  $$ brew tap $(REQ_BREW_TAPS)"; fi
	@if [[ "${OS}" -eq "Darwin"  ]] ; then echo "#  $$ brew install $(REQ_BREW_PACKAGES)"; fi

clean: \
	.clean-project \
	.clean-containers \
	.clean-images \

.clean-containers: stop
	# Kill running containers
	@$(COMPOSE) kill || true
	# Remove built docker containers
	@$(COMPOSE) rm --force -v || true

.clean-images: stop
	# Clean built docker images
	@$(DOCKER) images | $(GREP) -P '$(PROJECT)' \
		| $(SED) 's/  */ /g' | $(CUT) -d' ' -f3 \
		| $(XARGS) -rn1 $(DOCKER) rmi --force || true

.clean-project:
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf vendor'
	# Remove application dependencies: composer
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf vendor'
	# Remove application dependencies: node_modules
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf node_modules'
	# Remove application caches
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf var/cache/*'
	# Remove application logs
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf var/logs/*'
	# Remove application sessions
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf var/sessions/*'
	# Remove generated configurations
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf app/config/parameters.yml'

build:
	# Prepare host-specific docker-compose.yml
	$(CP) ${COMPOSE_FILE} docker-compose.yml
	# Docker Compose build
	@$(COMPOSE) build

clean-caches:
	# Clean application cache
	@$(COMPOSE) run --rm cli bash -c 'php bin/console cache:clear --no-warmup'

clean-logs:
	# Clean all log files
	@$(FIND) app/logs -type f \
		| $(XARGS) -I{} sh -c 'echo "Clean {}"; echo > {} || true' \
		2>/dev/null

install-dependencies:
	# Workaround for permission errors
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf node_modules'
	# Create cache, session and logs directories
	@$(MKDIR) -p vendor var/logs var/cache var/sessions || true
	# Install php dependencies
	@$(COMPOSE) run --rm cli bash -c 'composer install --ignore-platform-reqs'
	# Install node dependencies
	@$(COMPOSE) run --rm cli bash -c 'yarn'
	@$(COMPOSE) run --rm cli bash -c 'npm rebuild node-sass'

install: build install-dependencies install-database
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf var/cache/*'

install-database:
	# Drop the database schema
	@$(COMPOSE) run --rm cli bash -c 'php bin/console doctrine:schema:drop --force --em=default'
	# Create the database schema
	@$(COMPOSE) run --rm cli bash -c 'php bin/console doctrine:schema:create --em=default'

import-demo:
	# Import Demo Fixtures
	@$(COMPOSE) run --rm cli bash -c \
		'php bin/console doctrine:fixtures:load --fixtures=src/Yay/Bundle/DemoBundle -em=default --no-interaction'

dump-api-docs:
	# Dump the api documentation
	@$(COMPOSE) run --rm cli bash -c 'php bin/console api:doc:dump' > API.md


test: install-database
	# Run the testsuite
	@$(COMPOSE) run --rm cli bash -c 'vendor/bin/phpunit'

restart: stop run

run:
	# Start all containers
	@$(COMPOSE) up -d web
	#
	# The application should be up and running
	#   API:          http://localhost:50080/api/doc
	#   MySQL Server: localhost:53306
	#   Redis Server: localhost:56379

stop:
	# Stop all containers
	@$(COMPOSE) stop || true

watch-logs: .preflight-check
	# Watch log files
	@$(COMPOSE) run --rm cli bash -c 'tail -f var/logs/*'

build-assets:
	# Build assets
	@$(COMPOSE) run --rm cli bash -c 'node_modules/.bin/webpack --progress'

watch-assets:
	# Watch assets and build them
	@$(COMPOSE) run --rm cli bash -c 'node_modules/.bin/webpack --progress --watch'

watch-redis:
	# Monitor redis queries
	@$(COMPOSE) run --rm cli bash -c 'exec redis-cli -h redis -p 6379 monitor'

shell:
	# Start a interactive shell session
	@$(COMPOSE) run --rm --service-ports cli bash
