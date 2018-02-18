# @param integration
define .integration-enable
	@$(call .docker-run,cli,'\
		php bin/console doctrine:schema:drop --force --em=default && \
		php bin/console doctrine:schema:create --em=default && \
		php bin/console yay:integration:enable $(1) integration/$(1) && \
		php bin/console yay:recalculate && \
		php bin/console cache:clear --no-warmup && \
		php bin/console cache:warmup')
endef

# @param integration
define .integration-disable
	@$(call .docker-run,cli,'\
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

.integration-enable-gitlab:
	@$(call .integration-enable,gitlab)

.integration-disable-gitlab:
	@$(call .integration-disable,gitlab)

.integration-enable-bitbucket:
	@$(call .integration-enable,bitbucket)

.integration-disable-bitbucket:
	@$(call .integration-disable,bitbucket)

.integration-enable-travisci:
	@$(call .integration-enable,travisci)

.integration-disable-travisci:
	@$(call .integration-disable,travisci)

.integration-enable-jira:
	@$(call .integration-enable,jira)

.integration-disable-jira:
	@$(call .integration-disable,jira)