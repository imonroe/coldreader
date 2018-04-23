# Coldreader

Coldreader is a personal information management system. It's primarily aimed at people who are comfortable with basic web development using PHP and Javascript. It's built using Laravel 5.6, Bootstrap 3, and Vue.js.

This is the open-source version.  It is configured to support a single user, and includes some tools to make it easy to customize the system for your own uses.

It may be helpful to think of it as something half-way between an mindmap and a private wiki.  I use it in place of Evernote, as a personal information management tool. Out of the box, it may seem a little trivial, but once you start building it out with your own custom Aspects, you'll find that there's really no other tool like it.

It makes no assumptions about what kind of information you want to keep track.  In Coldreader, there are essentially two kinds of things: Subjects, and Aspects.

A Subject can have an arbitrary number of Aspects.  Aspects are like a single piece of content.  That may be text, an image, an API result, and so forth.  By itself, a basic Aspect just stores its information as text in the database.  But you can extend simple aspects with a little bit of code so that they can behave however you like.  When you create a Custom Aspect Type, Coldreader will automatically add boilerplate code for your new overridden Aspect into the app/CustomAspects.php file.  By modifying the boilerplate with your own custom logic, you can retrieve API results, perform a calculation, whatever you like.  For more information, see Developing Custom Aspects below.

Use cases:

- Project management system
- TODO list
- Personal CRM system -- stop forgetting your girlfriend's parents names, your friend's anniversary, etc.
- Manage your collection of media
- Track your notes and references as you research new things
- Create a knowledgebase of problems you have previously solved
- Keep a library of your favorite recipes
- API test bed - quickly add new features by implementing an API and using your exising data
- Organize your life however you like


## Dependencies 
- PHP 7.2+
- Composer (tested with version 1.6.4)
If you're planning to develop with Coldreader, you'll need Node and NPM for Webpack/Vue.JS support.
- Node.JS (tested with version 8.9.4)
- npm (tested with version 5.6.0)

## Install

Set up a directory to hold the installation.
Set up a url in nginx to correspond to the url you want to use.
Set up an empty database in mysql.  Remember the username and password.
Change directory to your installation directory.
composer create-project imonroe/coldreader
composer create-project imonroe/coldreader . 2.1.0.x-dev
wait for it to complete.
then run: 
run: php cr_configure.php
- or - 
run: git clone https://github.com/imonroe/coldreader.git .
[switch to the branch you want to use, and git pull if needed.]
run: php cr_configure.php
follow the prompts.  The script will configure your initial settings automatically.
It will ask you if you want to install npm dependencies.  In general, the answer should be yes, but you may want to skip that in some cases.
Composer will pull down all the dependencies.  This may take some time.

Coldreader is based on [Laravel 5.6](https://laravel.com/), and requires a server that is capable of serving a Laravel project.

For specific information about server requirements, [see the Laravel docs.](https://laravel.com/docs/5.6#server-requirements)

The Homestead vagrant box works just fine with Coldreader, and you can try it out in your local environment with no risk. For more information about using Laravel Homestead, [check out their fantasic documentation.](https://laravel.com/docs/5.6/homestead)

Once you have an environment set up, you'll want to create a directory to hold the project, and an empty database. Make sure you note the credentials you set up for the database to make installation easy.

The simple way to install everything is to use Composer.  If you plan on developing with Coldreader, you are encouraged to use the Git method.

###Via Composer:
``` bash
$ cd /wherever/you/want/to/install/
$ composer create-project imonroe/coldreader .
```
Wait for the installation to complete. Then run: 
``` bash
$ php cr_configure.php
```
Follow the prompts.  

###Via Git
``` bash
$ cd /wherever/you/want/to/install/
$ git clone https://github.com/imonroe/coldreader.git .
```
Wait for the installation to complete. Then run: 
``` bash
$ php cr_configure.php
```
Follow the prompts.

The configuration script will ask if you'd like to install the npm dependencies. If you are planning on developing with Coldreader, you may want to do that now.  It can take some time, however, and can safely be skipped if you're just trying out the software.

## Developing with Coldreader
For instructions and examples for how to get started developing with Coldreader, [please see the wiki.](https://github.com/imonroe/coldreader/wiki)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
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
