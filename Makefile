.PHONY: help

help:
	@grep -hE '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
	| sort \
	| awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help

build: ## install & update dependencies
	docker-compose run --rm -T php composer update

test: build ## run tests
	docker-compose run -T php php /app/vendor/bin/phpunit --configuration phpunit.dist.xml
