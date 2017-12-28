define .docker-compose-prepare
	@if [ -z ${BLACKFIRE_SERVER_ID+x} ]; then export BLACKFIRE_SERVER_ID=""; fi
	@if [ -z ${BLACKFIRE_SERVER_TOKEN+x} ]; then export BLACKFIRE_SERVER_TOKEN=""; fi
endef

# @param service
# @param command
# @param arguments
define .docker-run
    @$(call .docker-compose-prepare)
    @docker-compose -p $(PROJECT) run --rm $(3) $(1) bash -c $(2)
endef

# @param service
# @param arguments
define .docker-up
    @$(call .docker-compose-prepare)
    @docker-compose -p $(PROJECT) up $(2) $(1)
endef

# @param service
define .docker-upd
    @$(call .docker-compose-prepare)
    @docker-compose -p $(PROJECT) up -d $(1)
endef

.docker-build-images:
	@$(call .docker-compose-prepare)
	@docker-compose -p $(PROJECT) build

.docker-remove-containers:
	@$(call .docker-compose-prepare)
	@docker-compose -p $(PROJECT) kill || true
	@docker-compose -p $(PROJECT) rm --force -v || true

.docker-remove-images:
	@docker rmi $(shell docker images --format '{{.Repository}}' | grep '$(PROJECT)') --force

