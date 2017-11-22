.directories := \
	vendor,\
	var/cache/*,\
	var/logs/*,\
	var/sessions/* \
	config/integration/* \
	docker-run.sh

.clean-containers: \
	stop \
	.docker-remove-containers

.clean-images: \
	stop \
	.docker-remove-images

.clean-caches: \
	.application-remove-cache-dev \
	.application-remove-cache-test \
	.application-remove-cache-prod
    
.clean-project: stop
	@$(call .docker-run, cli, 'rm -rf $(.directories)')

.clean-database: \
	.application-clean-database