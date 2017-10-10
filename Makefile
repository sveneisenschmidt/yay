# Config
PROJECT := yay

all:
	#	start					Start the application
	#	stop					Stop the application
	#	restart					Restart the application
	#	build					Build the application including development environment
	#	clean					Cleans cache files, logs
	#	cleanall				Cleans containers, images and project files including caches, logs
	#	install					Install and build the application including development environment
	#	test					Run application tests
	#	shell					Start an interactive shell session
	#	demo-import				Import demo data
	#	demo-remove				Remove demo data
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

demo-import: .application-demo-import

demo-remove: .application-demo-remove

watch-logs: .application-watch-logs

watch-redis: .application-watch-redis
