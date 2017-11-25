# @param script
# @param image name
# @param env 
# @param branch
define .publish
	rm -rf var/* config/integration/*
	cp $(1) docker-run.sh
	chmod +x docker-run.sh
	docker build --squash --compress -t "$(2):$(3)" .
	docker push "$(2):$(3)"
	if [[ "$(3)" == "stable" ]]; then docker tag "$(2):$(3)" "$(2):latest" && docker push "$(2):latest"; fi
	if [[ "$(3)" == "dev" ]]; then docker tag "$(2):$(3)" "$(2):dev-$(4)" && docker push "$(2):dev-$(4)"; fi
	rm docker-run.sh
endef