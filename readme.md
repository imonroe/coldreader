# Coldreader

Coldreader is a casual, personal databasing system.

It may be helpful to think of it as something half-way between an mindmap and a private wiki.  I use it in place of Evernote, as a personal information management tool. Out of the box, it may seem a little trivial, but once you start building it out with your own custom Aspects, you'll find that there's really no other tool like it.

It makes no assumptions about what kind of information you want to keep track.  In Coldreader, there are essentially two kinds of things: Subjects, and Aspects.

A Subject can contain an arbitrary number of Aspects.  Aspects are like a single piece of content.  That may be text, an image, an API result, and so forth.  By itself, a basic Aspect just stores its information as text in the database.  But you can extend simple aspects with a little bit of code so that they can behave however you like. By modifying the boilerplate with your own logic, you can retrieve API results, perform a calculation, whatever you like.  For more information, see Developing Custom Aspects below.

Coldreader is primarily aimed at people who are comfortable with basic web development using PHP and Javascript. It's built using Laravel 5.6, Bootstrap 3, and Vue.js.

This is the open-source version.  It is configured to support a single user, and includes some tools to make it easy to customize the system for your own uses.

Use cases:

- Project management system
- Personal CRM system
- Build custom dashboards to track subjects of interest
- Manage your collection of media
- Track your notes and references as you research new things
- Create a knowledge base of problems you have previously solved
- Keep a library of your favorite recipes
- API test bed - quickly add new features by implementing an API and using your existing data
- Organize your data however you like

## Install

Coldreader is based on [Laravel 5.6](https://laravel.com/), and requires a server that is capable of serving a Laravel project, or any machine with Docker and Docker Compose installed.

For specific information about server requirements, [see the Laravel docs.](https://laravel.com/docs/5.6#server-requirements)

The Homestead vagrant box works just fine with Coldreader, and you can try it out in your local environment with no risk. For more information about using Laravel Homestead, [check out their fantasic documentation.](https://laravel.com/docs/5.6/homestead)

### Via docker-compose
By far, the easiest way to set up Coldreader on your local machine for development is to use the `docker-compose` file.  Naturally this requires Docker to be set up on the machine upon which you wish to run the software.
- Clone the repo to a convenient location.
- `cd` into the directory where you cloned the repo.
- Edit the `.env` file to suit your preferences.
- Run `docker-compose up`.  That will build the application stack.
- It may take quite some time to build the stack the first time you run `docker-compose up`.  This is normal.  Subsequent builds will be faster.
- The application will be available at: http://127.0.0.1.  You can access Adminer to manipulate the database at: http://127.0.0.1:8080
- Need to run `artisan` commands?  Need to rebuild the assets with webpack?  You can do so within the docker container using the normal methods.  To get a `bash` shell:
    - On a Windows system, just run `larabash.bat`
    - On Macs and *nix-like systems, just run `docker-compose exec laravel bash`

### Installing additional add-ons

One of the nice things about Coldreader is that it's easy to create new Aspect Types and Search Providers to accomodate different kinds of data, and different ways of displaying it. There are some Aspect Types already available to try.

To install Coldreader add-on packages, use composer within the application container:
``` bash
$ composer require <vendor>/<package_name>
$ composer update
```
Some add-on packages may require additional configuration. Consult the package repo for details for any individual add-on.

#### Add-ons currently available:
- COMING SOON!


## Developing with Coldreader
For instructions and examples for how to get started developing with Coldreader, [please see the wiki.](https://github.com/imonroe/coldreader/wiki)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing
From within `bash` in the application container:
``` bash
$ cd application
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email ian@ianmonroe.com instead of using the issue tracker.

## Credits

- [Ian Monroe][link-author]
- [All Contributors][link-contributors]

## License

GPL V3. Please see [License File](LICENSE.md) for more information.

[link-packagist]: https://packagist.org/packages/imonroe/coldreader
[link-author]: https://github.com/imonroe
[link-contributors]: ../../contributors
