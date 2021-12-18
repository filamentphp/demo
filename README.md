# Filament Demo App

A demo application to illustrate how Filament Admin works.

![](https://user-images.githubusercontent.com/21066418/146419930-356e5ebd-9669-499c-94c0-666537ea118e.png)

## Installation

#### Clone the repo locally:

```sh
https://github.com/laravel-filament/demo.git filament-demo
cd filament-demo
```

#### Install PHP dependencies:

```sh
composer install
```

or you can use docker compose:

```sh
docker-compose up -d --build
```

#### Setup configuration:

```sh
cp .env.example .env
```

#### Generate application key:

```sh
php artisan key:generate
```

if you use docker compose then

```sh
docker-compose exec app php artisan key:generate
```

#### Database Configuration:

Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

for other database configuration `DB_HOST` should be `host.docker.internal` in `.env`.

#### Run database migrations:

```sh
php artisan migrate
```
if you use docker compose then 

```sh
docker-compose exec app php artisan migrate
```

#### Run database seeder:

```sh
php artisan db:seed
```
if you use docker compose then

```sh
docker-compose exec app php artisan db:seed
```

#### Run the dev server (the output will give the address):

```sh
php artisan serve
```

if you use docker compose then visit `http://localhost:8000`. you can change the port in `docker-compose.yml` or add `APP_PORT` in `.env`.

You're ready to go! Visit the url in your browser, and login with:

-   **Username:** admin@filamentadmin.com
-   **Password:** password
