# Coldreader

![Latest Version on Packagist[ico-version]]
![Total Downloads[ico-downloads]] [link-downloads]

Coldreader is a personal information management software package. It's primarily aimed at people who are comfortable with basic web development using PHP and Javascript. It's built using Laravel 5.4, Bootstrap, and JQuery.

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
- Laravel 5.4
- jQuery

## Install

Via Composer

``` bash
$ composer create-project imonroe/coldreader
```
Via Git
``` bash
$ git clone https://github.com/imonroe/coldreader.git
$ cd coldreader
$ composer update
$ cp .env.example .env
```
Then edit your .env file to add your database credentials
``` bash
$ php artisan migrate
$ php artisan cliuser:create
```
Fire up your web browser and hit the site.  You can log in with the account you just created.

## Developing Custom Aspect Types

### Anatomy of an Aspect

#### Properties:

- public int id
The ID of this aspect

- public int aspect_type
The aspect_type id.  This determines the aspect_type we load this as.

- public longtext aspect_data
This is where the aspect's data is actually stored.  Note it's a longtext field.  Any data that an aspect stores, should be in text. Default is UTF-8

- public mediumtext aspect_notes
aspect_notes holds an array of configuration options for this array.  This is stored in JSON format in the database. The schema for this data is specified in the notes_schema() function below.

- public text aspect_source
An arbitrary text field.  This exists so you can record where and when you collected this information.

- public int hidden
0 = hidden, 1 = visible.

- public datetime last_parsed
The last time the parse() function was triggered.  See parse() below.

- public timestamp created_at
- public timestamp updated_at
The standard Laravel timestamps

- public int display_weight
Display weight determines the order aspects are displayed. Higher values push aspects down, lower values let them float up.  Default is 100.

- public text title
A text field that holds an optional title for this aspect.

- public int folded
0 = not folded, 1 = folded.

##### Useful Methods:
    
- public function notes_schema()

- public function isSubclass()

- public function update_aspect()

- public function aspect_type()

- public function notes_fields()

- public function create_form($subject_id, $aspect_type_id=null)

- public function edit_form($id)

- public function subjects()

- public function display_aspect()

- public function parse()

- public function pre_save(Request $request)

- public function post_save(Request $request)

- public function pre_update(Request $request)

- public function post_update(Request $request)

- public function pre_delete(Request $request)


Upon creating a custom Aspect Type, boilerplate code will be added to the file app/CustomAspects.php

If you created an Aspect Type called "Demo", for instance, the following code will be added: 

``` php
class DemoAspect extends Aspect{
	public function notes_schema(){
		return parent::notes_schema();
	}
	public function create_form($subject_id, $aspect_type_id=null){
		return parent::create_form($subject_id, $this->aspect_type);
	}
	public function edit_form($id){
		return parent::edit_form($id);
	}
	public function display_aspect(){
		$output = parent::display_aspect();
		return $output;
	}
	public function parse(){}
}  // End of the DemoAspectclass.
```



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

[ico-version]: https://img.shields.io/packagist/v/imonroe/crps.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/imonroe/crps.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/imonroe/coldreader
[link-author]: https://github.com/imonroe
[link-contributors]: ../../contributors
