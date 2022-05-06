SHELL := /bin/sh

database:
	@echo "Creating database for dev environment..."

	php bin/console doctrine:database:drop --force --env=dev || true
	php bin/console doctrine:database:create --env=dev
	php bin/console doctrine:schema:create --env=dev

	@echo "Database created. Loading data fixtures..."

	php bin/console doctrine:fixtures:load --env=dev -n

	@echo "Fixtures loaded"

	

	@echo "Creating database for test environment..."

	php bin/console doctrine:database:drop --force --env=test || true
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine:schema:create --env=test

	@echo "Database created. Loading data fixtures..."

	php bin/console doctrine:fixtures:load --env=test -n

	@echo "Fixtures loaded"
