# Development

## Debugging

Setup debugging by [https://gist.github.com/Raistlfiren/d4286169b7223054a6b23c169ee3f182][instruction].
Additional setup [https://youtrack.jetbrains.com/issue/WI-45123#focus=Comments-27-3297758.0-0][example].

To get your IP address: `hostname -I | awk '{print $1}'`

## Install Laravel

`docker-compose run app sh`
`composer create-project laravel/laravel .`

## Allow editing files
`sudo chown -R $USER:$USER .`

## Server name
`http://localhost:8000`

## Migrations

1. `docker-compose run app php artisan migrate:install`
2. `docker-compose run app php artisan migrate`

## NPM (front)

1. `docker-compose run app npm install`
1. `docker-compose run app npm run dev`