# Royalty Manager API - DEMO

Record and calculate royalty payments owed to Rights Owners based on viewing activity of customers.

## Getting Started

1. Install [Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker-compose build --pull --no-cache` to build fresh images
3. Run `docker-compose up` (the logs will be displayed in the current shell)
4. Run `docker-compose down` to stop the Docker containers.


## Doctrine Fixtures Load

In order to load testing data into the database, you have to run this commands:

```bash
$ docker exec -i  royaltymanager-api-demo_php_1 sh -c "make database"
```

The command above, loads sample data into "royaltymanager_api" and "royaltymanager_api_test" databases. First one it's intended to work with dev environment, while sencond one, is intended to work with test environments. This way, "real" database data are not affected when Functional Tests are executed.

## Execute tests

In order to run tests, you have to run the fallowing command:

```bash
docker exec -i  royaltymanager-api-demo_php_1 sh -c "php /srv/app/bin/phpunit"
```