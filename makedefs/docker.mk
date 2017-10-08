# @param service
# @param command
# @param arguments
define .docker-run
    @docker-compose -p $(PROJECT) run --rm $(3) $(1) bash -c $(2)
endef

# @param service
# @param arguments
define .docker-up
    @docker-compose -p $(PROJECT) up $(2) $(1)
endef

# @param service
define .docker-upd
    @docker-compose -p $(PROJECT) up -d $(1)
endef

.docker-build-images:
	@docker-compose -p $(PROJECT) build

.docker-remove-containers:
	@docker-compose -p $(PROJECT) kill || true
	@docker-compose -p $(PROJECT) rm --force -v || true

.docker-remove-images:
	@docker images | $(GREP) -P '$(PROJECT)' \
		| $(SED) 's/  */ /g' | $(CUT) -d' ' -f3 \
		| $(XARGS) -rn1 docker rmi --force || true
