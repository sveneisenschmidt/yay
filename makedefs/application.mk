.application-remove-cache-dev:
	@$(call .docker-run,cli,'php bin/console cache:clear --env=dev --no-warmup')

.application-remove-cache-test:
	@$(call .docker-run,cli,'php bin/console cache:clear --env=test --no-warmup')

.application-remove-cache-prod:
	@$(call .docker-run,cli,'php bin/console cache:clear --env=prod --no-warmup')

.application-install-dependencies:
	@mkdir -p .build .build/cache vendor var/logs var/cache var/sessions || true
	@$(call .docker-run,cli,'composer install --ignore-platform-reqs')

.application-clean-database:
	@$(call .docker-run,cli,'\
		php bin/console doctrine:schema:drop --force --em=default && \
		php bin/console doctrine:schema:create --em=default')

.application-install-database:
	@$(call .docker-run,cli,'\
		php bin/console doctrine:schema:drop --force --em=default && \
		php bin/console doctrine:schema:create --em=default')

.application-demo-import: \
	.application-clean-database \
	.application-demo-import-fixtures \
	.application-remove-cache-dev

.application-demo-remove: \
	.application-demo-remove-fixtures \
	.application-clean-database \
	.application-remove-cache-dev

.application-demo-import-fixtures:
	@$(call .docker-run,cli,'\
		php bin/console yay:integration:enable default integration/default && \
		php bin/console yay:integration:enable demo integration/demo && \
		php bin/console yay:recalculate')

.application-demo-remove-fixtures:
	@$(call .docker-run,cli,'\
        php bin/console yay:integration:disable default && \
		php bin/console yay:integration:disable demo')

.application-test: .application-clean-database
	@$(call .docker-run,cli,'vendor/bin/phpunit')

.application-test-coverage: .application-clean-database
	@$(call .docker-run,cli,'vendor/bin/phpunit --coverage-text')

.application-build-docs:
	@$(call .docker-run,cli,'php bin/console api:doc:dump') > docs/api.md

.application-watch-logs:
	@$(call .docker-run,cli,'tail -f var/logs/*')

.application-watch-redis:
	@$(call .docker-run,redis,'exec redis-cli -h redis -p 6379 monitor')
