# Overview
This project returns a list of locations that fall within `n` kilometers of a coordinate.

# Setup
## System Requirements
| Software | Version      |
|----------|--------------|
| PHP      | ^7.3 or ^8.0 |

For starters, we're going to have to install our dependencies:
```shell
$ composer install
```

## Environment Variables
Now, the environment variables:
```shell
$ cp .env.example .env
```

Then, modify these database environment variables:
* `DB_HOST`
* `DB_PORT`
* `DB_DATABASE`
* `DB_USERNAME`
* `DB_PASSWORD`

## Application Key
Laravel uses application keys to encrypt cookies, so this is basically required. You can generate one by running:
```shell
$ php artisan key:generate
```

## Database
We have made a database migration for the locations dataset that we are going to use. You can add them to the database by running:
```shell
$ php artisan migrate --seed
```

## Running the app locally
When running the application locally, just run:
```shell
$ php artisan serve
```

# Usage
This app can only do calculations of locations, so it only has one (1) route:

| Verb   | URI                             | Parameters                                                                                                                                                                                   |
|--------|---------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **GET** | `/api/locations/within-radius` | 1. `lat` = Latitude of the coordinates <br/>2. `long` = Longitude of the coordinates<br/>3. `rad` = The radius (in kilometers) of the coordinates where the returned locations should be at. |

# Development
## Unit Tests
This project has already been unit tested. Try them out by running:
```shell
$ vendor/bin/phpunit
```

> Note: When running the artisan local server, make sure to point the `APP_URL` environment variable to the appropriate URL and port. i.e., `http://localhost:8000`
