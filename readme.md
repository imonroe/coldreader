# laravel_docker_starterkit

This started life as an example app for [CapRover](https://github.com/caprover/caprover), to which I have made a few modifications.

## What's included?

- Laravel 5.8.x
- the basic Laravel auth scaffolding
- mysql 5.7.22
- nginx
- a helper file for windows, `larabash.bat`, which launches you a nice bash window in the laravel container for things like `artisan` commands.
- npm 10.x
- a basic package.lock file supporting the default laravel `webpack.mix.js` configuration.
- a basic `captain-definition` file for CapRover deployments
- a modified docker file which will do a `composer install` and a `npm run dev` as part of the build process.
- Adminer, for doing any manual database stuff you may need.

## How do I use it?

Make sure you have Docker installed and working for your system.

Clone this repo to a convenient directory

Edit the `.env` file in the root of the repo.  Edit this line:
```
COMPOSE_PROJECT_NAME=test_app
```
To be the name of your new app.

Run `docker-compose up -d --build`

On my test system, it takes about 15 minutes to build the stack the first time.  Subsequent builds will be faster, because caching.

Code away.  You'll find the laravel install in the `application` folder.

Your app will be available at http://127.0.0.1:80

Adminer will be available at http://127.0.0.1:8080

### Common tasks

How do I run `artisan` commands?
- If you're on windows, run `larabash.bat`.  That'll get you into a bash shell, and you can run `php artisan whatever...` just like normal.
- If you're on a unix-like system, just run `docker-compose exec laravel bash`.

How do I run `npm install`, etc.?
- See above to get into bash, then just run the commands as normal.


#### IMPORTANT:
Note that the build process for laravel projects are quite heavy, you need at least 2GB, or in some instances 4gb of RAM or your server might crash.

