# Filament Demo App

A demo application to illustrate how Filament Admin works.

![Filament Demo](https://user-images.githubusercontent.com/21066418/153760666-8dfb0725-4a5f-4a62-abd7-af396e73ff5d.png)

## Installation

Clone the repo locally:

```sh
https://github.com/laravel-filament/demo.git filament-demo
cd filament-demo
```

Install PHP dependencies:

```sh
composer install
```
or using Docker for composer
```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```

Using [Sail](https://laravel.com/docs/sail)
```sh
./vendor/bin/sail up -d
./vendor/bin/sail shell
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

Run the dev server (the output will give the address):

```sh
php artisan serve
```

You're ready to go! Visit the url in your browser, and login with:

-   **Username:** admin@filamentphp.com
-   **Password:** password
