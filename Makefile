SAIL=./vendor/bin/sail

help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(firstword $(MAKEFILE_LIST)) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

install: ## Install application
	docker run --rm -u "$$(id -u):$$($(id -g))" -v "$$(pwd):/var/www/html" -w /var/www/html laravelsail/php84-composer:latest composer install --ignore-platform-reqs
	cp .env.example .env
	${SAIL} up -d
	${SAIL} php artisan key:generate
	${SAIL} php artisan migrate:fresh
	${SAIL} php artisan db:seed
	${SAIL} php artisan storage:link
	${SAIL} php artisan cache:clear
	${SAIL} php artisan config:clear
	${SAIL} php artisan route:clear
	${SAIL} php artisan view:clear
	${SAIL} npm install
	${SAIL} npm run build

up: ## Start application
	${SAIL} up

down: ## Stop application
	${SAIL} down

test: ## Run tests
	${SAIL} artisan test

ide-helper: ## Generate IdeHelper files
	${SAIL} php artisan ide-helper:eloquent
	${SAIL} php artisan ide-helper:generate --no-interaction
	${SAIL} php artisan ide-helper:meta
	${SAIL} php artisan ide-helper:models --write-mixin --no-interaction

reset-db: ## Reset database
	${SAIL} php artisan migrate:fresh --seed
