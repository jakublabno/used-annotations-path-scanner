.PHONY: help

help:
	@grep -hE '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
	| sort \
	| awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help


install-dependencies: ##install and update composer dependencies
	docker-compose run --rm -T php composer update


test-php7.4: ## run tests
	docker-compose run --rm -T php-7.4 composer update
	docker-compose run --rm -T php-7.4 php vendor/bin/phpunit --configuration phpunit.dist.xml

test-php8.0: ## run tests
	docker-compose run --rm -T php-8.0 composer update
	docker-compose run --rm -T php-8.0 php vendor/bin/phpunit --configuration phpunit.dist.xml
