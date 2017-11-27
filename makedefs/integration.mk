# @param integration
define .integration-enable
	@$(call .docker-run,cli,'\
		php bin/console doctrine:schema:drop --force --em=default && \
		php bin/console doctrine:schema:create --em=default && \
		php bin/console cache:clear --no-warmup && \
		php bin/console yay:integration:enable default integration/default && \
		php bin/console yay:integration:enable $(1) integration/$(1) && \
		php bin/console yay:recalculate && \
		php bin/console cache:clear --no-warmup && \
		php bin/console cache:warmup')
endef

# @param integration
define .integration-disable
	@$(call .docker-run,cli,'\
		php bin/console yay:integration:disable default && \
		php bin/console yay:integration:disable $(1) && \
		php bin/console doctrine:schema:drop --force --em=default && \
		php bin/console doctrine:schema:create --em=default && \
		php bin/console cache:clear --no-warmup && \
		php bin/console cache:warmup')
endef

.integration-enable-demo:
	@$(call .integration-enable,demo)

.integration-disable-demo:
	@$(call .integration-disable,demo)

.integration-enable-github:
	@$(call .integration-enable,github)

.integration-disable-github:
	@$(call .integration-disable,github)