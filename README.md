# Royalty Manager API - DEMO

Record and calculate royalty payments owed to Rights Owners based on viewing activity of customers.

## Getting Started

1. Install [Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker-compose build --pull --no-cache` to build fresh images
3. Run `docker-compose up -d`  (the containers will be running an you can still using the same terminal)


## Doctrine Fixtures Load

In order to load testing data into the database, you have to run this command:

```bash
$ docker exec -i  royaltymanager-api-demo_php_1 sh -c "make database"
```

The command above, loads sample data into "royaltymanager_api" and "royaltymanager_api_test" databases. First one is intended to work with dev environment, while second one, is intended to work with test environments. This way, "real" database data are not affected when Functional Tests are executed.

## Endpoints

Please, refer to the following link to view the API docs and endpoints available:

https://documenter.getpostman.com/view/11366921/UyxdKU7R

## Execute tests

In order to run tests, you have to run the fallowing command:

```bash
docker exec -i  royaltymanager-api-demo_php_1 sh -c "php /srv/app/bin/phpunit"
```