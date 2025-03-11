.DEFAULT_GOAL := help
DC = docker-compose
RUN_PHP = $(DC) run --rm --no-deps php /bin/sh -c

.PHONY: help
help: ## Display this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: setup
setup: ## Rebuild containers
	@echo
	@echo "--- Build containers"
	$(DC) up --detach --force-recreate --build --remove-orphans

.PHONY: up
up: ## Starting containers
	@echo
	@echo "--- Stopping containers..."
	$(DC) up --detach

.PHONY: stop
stop: ## Stop and remove the docker compose services
	@echo
	@echo "--- Stopping containers..."
	$(DC) stop

.PHONY: terminal
terminal: ## Start a bash terminal
	$(RUN_PHP) "bash"

.PHONY: composer-install
composer-install: ## Install PHP composer dependencies (/vendors)
	@echo
	@echo "--- Installing PHP dependencies (/vendors)..."
	$(RUN_PHP) "composer install"

.PHONY: composer-dump-autoload
composer-dump-autoload: ## Dump/rebuild the composer autoload
	$(RUN_PHP) "composer dump-autoload -o -a"

