# play list
clone this project or download it.
## requirment
* php 7.4
* composer
* mysql 5.7

## Settings
make sure to change the .env file according to your system database and other variables
## To run the app
```
// open app in terminal
$ cd {project_dir}/playlist_app
$ composer install

// initialize database 
$ bin/console doctrine:database:create
$ bin/console make:migrations
$ bin/console doctrine:migrations:migrate

// Serve the app using the php build in server
$ php -S 127.0.0.1:8000 -t public
```

## Imports
```
// Importing Users
$ bin/console app:import:users
// Importing Songs/Mp3s
$ bin/console app:import:mp3
// Importing Playlists
$ bin/console app:import:playlists
```
## urls
* hompage --- http//:localhost:8000
* users list --- http//:localhost:8000/users
* json end point --- http//:localhost:8000/api/v1/users
## Api Platform docs
* swagger api docs --- http//:localhost:8080/api/docs/