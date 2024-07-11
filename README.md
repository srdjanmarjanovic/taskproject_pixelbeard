# PixelBeard.co test task project



**_Prerequisits_**

You will need to have Docker, PHP 8.3 and composer installed to continue with this setup. 

## Setup and usage with Docker using Laravel Sail
- git clone this repo `git clone git@github.com:srdjanmarjanovic/taskproject_pixelbeard.git`
- enter the new directory `cd taskproject-pixelbeard`
- execute `composer install` to install dependencies
- copy `.env.example` to `.env` with executing `cp .env.example .env`
- execute `./vendor/bin/sail up -d` to build and start PHP and MySQL container. This might take a moment.
- generate app key `./vendor/bin/sail artisan key:generate`
- execute `./vendor/bin/sail composer install` to install dependencies with proper platform requirements
- execute `./vendor/bin/sail artisan migrate --seed` to run the migrations. `--seed` option will create an initial user. This user will be used as a mock of authenticated user for creating the relationship with `Task` models
- execute `./vendor/bin/sail test` to run all tests

**API documentation is available on the following endpoint:** [http://localhost/docs/api](http://localhost/docs/api)
