.application-remove-cache-dev:
	@$(call .docker-run,cli,'\
        php bin/console cache:clear --env=dev --no-warmup && \
        php bin/console cache:warmup --env=dev')

.application-remove-cache-test:
	@$(call .docker-run,cli,'\
        php bin/console cache:clear --env=test --no-warmup && \
        php bin/console cache:warmup --env=test')

.application-remove-cache-prod:
	@$(call .docker-run,cli,'\
        php bin/console cache:clear --env=prod --no-warmup && \
        php bin/console cache:warmup --env=prod')

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

.application-test:
	@$(call .docker-run,cli,'\
		php bin/console doctrine:schema:drop --env=test --force --em=default && \
		php bin/console doctrine:schema:create --env=test --em=default && \
        vendor/bin/phpunit')

.application-test-coverage:
	@$(call .docker-run,cli,'\
		php bin/console doctrine:schema:drop  --env=test --force --em=default && \
		php bin/console doctrine:schema:create  --env=test --em=default && \
        phpdbg -qrr ./vendor/bin/phpunit \
            --coverage-text \
            --coverage-html=.build/report \
            --coverage-clover=coverage.xml')

.application-watch-logs:
	@$(call .docker-run,cli,'tail -f var/logs/*')

.application-watch-redis:
	@$(call .docker-run,redis,'exec redis-cli -h redis -p 6379 monitor')
