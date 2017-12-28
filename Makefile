# Config
PROJECT := yay
DOCKER_ENV ?= dev

all:
	#    start                Start the application
	#    stop                 Stop the application
	#    restart              Restart the application
	#    build                Build the application including development environment
	#    clean                Cleans cache files, logs
	#    cleanall             Cleans containers, images and project files including caches, logs
	#    install              Install and build the application including development environment
	#    test                 Run application tests
	#    test-coverage        Run application tests and generate code coverage
	#    shell                Start an interactive shell session
	#    default-publish      Publish demo docker image to sveneisenschmidt/yay
	#    enable-demo          Import demo data
	#    disable-demo         Remove demo data
	#    enable-github        Import demo data
	#    disable-github       Remove demo data
	#    demo-publish         Publish demo docker image to sveneisenschmidt/yay-demo
	#    watch-logs           Watch all log files
	#    watch-redis          Watch all redis queries

# Includes
include makedefs/*.mk

# Targets
restart: stop start

start:
	@$(call .docker-upd,web)
	@sleep 5
	# http://localhost:50080

start-attached:
	@$(call .docker-up,web)

stop:
	@docker-compose -p $(PROJECT) stop || true

shell:
	@$(call .docker-run,cli,'bash',--service-ports)

clean: .clean-project .clean-containers

cleanall: .clean-project .clean-containers .clean-images

install: build .application-install-dependencies .application-install-database

build: .docker-build-images

build-docs: .application-build-docs

test: .application-test

test-coverage: .application-test-coverage

enable-demo: .integration-enable-demo

disable-demo: .integration-disable-demo

enable-github: .integration-enable-github

disable-github: .integration-disable-github

publish-default:
	@$(call .publish,dist/docker-run.default.sh,sveneisenschmidt/yay,$(DOCKER_ENV),$(DOCKER_BRANCH))

publish-demo:
	@$(call .publish,dist/docker-run.demo.sh,sveneisenschmidt/yay-demo,$(DOCKER_ENV),$(DOCKER_BRANCH))

watch-logs: .application-watch-logs

watch-redis: .application-watch-redis
