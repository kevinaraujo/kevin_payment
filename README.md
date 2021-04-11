# Kevin Payment App

This project based on reactJS to front end (I could't finish it becuse of time) and Laravel to backend. I'm using docker and docker-compose to run.

## Getting Started

First of all, you need to run the docker command below to setup the web and api projects:

### `docker-compose up -d`

Open [http://localhost:3010](http://localhost:3010) to view it in the browser.

After up your containers, run:
`docker-compose exec api php artisan key:generate`

Replace db connection environment variables on your **.env** file for these:
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=kevin_payment
DB_USERNAME=root
DB_PASSWORD=root

SECRET_KEY=kevin_payment
API_TRANSACTION_VALIDATION=https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6
```

The **SECRET_KEY** variable is used to generate a JWT to use in private routes;

## Migrate and Seed Database

Run the following command to build your database with initial informations: `docker-compose exec api php artisan migrate:fresh --seed`

## Documentation

To see which endpoints is available in api project, paste the content from `/api/docs/swagger.yml` into [https://editor.swagger.io/](https://editor.swagger.io/) website.

## Tests

To run integration and unit tests, access the api container with command `docker-compose exec`. 

After access the container, run `php -dxdebug.mode=coverage ./vendor/bin/phpunit --testdox --coverage-html reports/`.

A test coverage report file will be generated in **api/reports/index.html**. Open it in your web browser.

If you want test the endpoint via postman, import the `postman.json` from `/api/docs` to your postman software.
