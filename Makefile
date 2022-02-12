.PHONY: help

help:
	@grep -hE '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
	| sort \
	| awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help


install-dependencies: ##install and update composer dependencies
	docker-compose run --rm -T php composer update


test: install-dependencies ## run tests
	docker-compose run --rm -T php php vendor/bin/phpunit --configuration phpunit.dist.xml
