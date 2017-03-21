
MAKEFLAGS += --warn-undefined-variables
OS := $(shell uname)
SHELL := bash
.SHELLFLAGS := -eu -o pipefail -c
.DEFAULT_GOAL := all
.DELETE_ON_ERROR:
.SUFFIXES:
.PHONY: .preflight-check

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
	#   mc                        Browse container files with mdinight commaner
	#
	#   clean                     Clean all build outputs
	#   clean-containers          Remove the built docker-compose containers
	#   clean-images              Remove the built docker images
	#   clean-dependencies        Clean all installed dependencies and caches
	#   clean-logs                Clean all log files
	#   clean-caches              Clean and restart the Redis database and clear the application cache
	#   distclean                 Run all clean targets
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



distclean: \
	clean-project \
	clean-docker-containers \
	clean-docker-images

clean: \
	clean-project \
	clean-docker-containers

clean-docker-containers: stop
	# Kill running containers
	@$(COMPOSE) kill || true
	# Remove built docker containers
	@$(COMPOSE) rm --force -v || true

clean-docker-images: stop
	# Clean built docker images
	@$(DOCKER) images | $(GREP) -P '$(PROJECT)' \
		| $(SED) 's/  */ /g' | $(CUT) -d' ' -f3 \
		| $(XARGS) -rn1 $(DOCKER) rmi --force || true

clean-project:
	# Remove application dependencies: composer
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf vendor'
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf .data/vendor'
	# Remove application dependencies: node_modules
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf node_modules'
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf .data/node_modules'
	# Remove application caches
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf var/cache/*'
	# Remove application logs
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf var/logs/*'
	# Remove application sessions
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf var/sessions/*'
	# Remove generated configurations
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf app/config/parameters.yml'

clean-caches:
	# Clean application cache
	@$(COMPOSE) run --rm cli bash -c 'php bin/console cache:clear --no-warmup'

clean-logs:
	# Clean all log files
	@$(FIND) app/logs -type f \
		| $(XARGS) -I{} sh -c 'echo "Clean {}"; echo > {} || true' \
		2>/dev/null

install-dependencies:
	# Create cache, session and logs directories
	@$(MKDIR) -p vendor var/logs var/cache var/sessions || true
	# Install php dependencies
	@$(COMPOSE) run --rm cli bash -c 'composer install'
	# Install node dependencies
	@$(COMPOSE) run --rm cli bash -c 'yarn'
	@$(COMPOSE) run --rm cli bash -c 'npm rebuild node-sass'

sync-dependencies:
    # Create .data directory
	@$(MKDIR) -p .data || true
    # vendor
	@$(COMPOSE) run --rm cli bash -c 'rsync -rtuz --delete-delay --progress --exclude="bin/" vendor/ .data/vendor'
    # node_modules
	@$(COMPOSE) run --rm cli bash -c 'rsync -rtuz --delete-delay --progress --exclude=".bin/" --exclude=".cache/" node_modules/ .data/node_modules'

install: install-dependencies sync-dependencies
	@$(COMPOSE) run --rm cli bash -c 'rm  -rf var/cache/*'

test:
	# Run the testsuite
	@$(COMPOSE) run --rm cli bash -c 'vendor/bin/phpunit'

restart: stop run

run:
	# Start all containers
	@$(COMPOSE) up -d web
	#
	# The application should be up and running
	#   web server: http://localhost:9406/
	# MySQL Server: 127.0.0.1:4407
	@bash -c 'sleep 1 && open http://localhost:9406'

stop:
	# Stop all containers
	@$(COMPOSE) stop || true

show-files:
	# Starts file manager
	@$(COMPOSE) run --rm cli bash -c 'EDITOR=nano ranger'

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
