# Config
PROJECT := yay
DOCKER_ENV ?= dev

all:
	#	start					Start the application
	#	stop					Stop the application
	#	restart					Restart the application
	#	build					Build the application including development environment
	#	clean					Cleans cache files, logs
	#	cleanall				Cleans containers, images and project files including caches, logs
	#	install					Install and build the application including development environment
	#	test					Run application tests
	#	test-coverage			Run application tests and generate code coverage
	#	shell					Start an interactive shell session
	#	default-publish			Publish demo docker image to sveneisenschmidt/yay
	#	demo-import				Import demo data
	#	demo-remove				Remove demo data
	#	demo-publish			Publish demo docker image to sveneisenschmidt/yay-demo
	#	watch-logs				Watch all log files
	#	watch-redis				Watch all redis queries

# Includes
include makedefs/*.mk

# Targets
restart: stop start

start:
	@$(call .docker-upd,web)
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

demo-import: .application-demo-import

demo-remove: .application-demo-remove

default-publish:
	rm -rf var/* config/integration/*
	cp dist/docker-run.default.sh docker-run.sh
	chmod +x docker-run.sh
	docker build --squash --compress -t sveneisenschmidt/yay:$(DOCKER_ENV) .
	docker push sveneisenschmidt/yay:$(DOCKER_ENV)
	if [ "$(DOCKER_ENV)" == "stable" ]; then \
		docker tag sveneisenschmidt/yay:$(DOCKER_ENV) sveneisenschmidt/yay:latest && \
		docker push sveneisenschmidt/yay:latest; \
	fi
	if [ "$(DOCKER_ENV)" == "dev" ]; then \
		docker tag sveneisenschmidt/yay:$(DOCKER_ENV) sveneisenschmidt/yay:dev-$(shell git rev-parse --abbrev-ref HEAD) && \
		docker push sveneisenschmidt/yay:dev-$(shell git rev-parse --abbrev-ref HEAD); \
	fi
	rm docker-run.sh

demo-publish:
	rm -rf var/* config/integration/*
	cp dist/docker-run.demo.sh docker-run.sh
	chmod +x docker-run.sh
	docker build --squash --compress -t sveneisenschmidt/yay-demo:$(DOCKER_ENV) .
	docker push sveneisenschmidt/yay-demo:$(DOCKER_ENV)
	if [ "$(DOCKER_ENV)" == "stable" ]; then \
		docker tag sveneisenschmidt/yay-demo:$(DOCKER_ENV) sveneisenschmidt/yay-demo:latest && \
		docker push sveneisenschmidt/yay-demo:latest; \
	fi
	if [ "$(DOCKER_ENV)" == "dev" ]; then \
		docker tag sveneisenschmidt/yay-demo:$(DOCKER_ENV) sveneisenschmidt/yay-demo:dev-$(shell git rev-parse --abbrev-ref HEAD) && \
		docker push sveneisenschmidt/yay-demo:dev-$(shell git rev-parse --abbrev-ref HEAD); \
	fi
	rm docker-run.sh

watch-logs: .application-watch-logs

watch-redis: .application-watch-redis
