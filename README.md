# RestFul Api for Pizza Ordering

> A Laravel API which enables customers order from menu

## Description
This project was built with Laravel and MySQL.

##### Integration testing :
- PHPUnit (https://phpunit.de)
- Faker (https://github.com/fzaninotto/Faker)

## Requirements
To run the API, you must have:
- **PHP** (https://www.php.net/downloads)
- **MySQL** (https://dev.mysql.com/downloads/installer)

## Running the API

Create an `.env` file using the command. You can use this config or change it for your purposes. 

```console
$ cp .env.example .env
```

### Environment
Configure environment variables in `.env` for dev environment based on your MYSQL database configuration

```  
DB_CONNECTION=<YOUR_MYSQL_TYPE>
DB_HOST=<YOUR_MYSQL_HOST>
DB_PORT=<YOUR_MYSQL_PORT>
DB_DATABASE=<YOUR_DB_NAME>
DB_USERNAME=<YOUR_DB_USERNAME>
DB_PASSWORD=<YOUR_DB_PASSWORD>
```

### Installation
Install the dependencies and start the server

```console
$ composer install
$ php artisan key:generate
$ php artisan migrate --seed
$ php artisan passport:install
$ php artisan serve
```

## Testing 
To run integration tests: 
```console
$ composer test
```
