<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use imonroe\ana\Ana;
use GuzzleHttp\Client;
use ZipArchive;

class ColdreaderBoilerplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coldreader:new {type : may be [aspect_type]}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates boilerplate code for adding new functionality to Coldreader.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Let\'s create a new package.');

        $bar = $this->output->createProgressBar(25);

        $cwd = getcwd();
        $type = $this->argument('type');
        $vendor = $this->ask('What is the vendor name for this package?');
        $package_name = $this->ask('What is the package name for this package?');
        $package_description = $this->ask('Give me a short description of the package.');
        $author_name = $this->ask('What is your name?');
        $author_email = $this->ask('What is your email address?');
        $author_github = $this->ask('What is your github username?');
        $packages_path = $cwd.'/packages';
        $boilerplate_template_path = $cwd.'/resources/boilerplate_templates';

        $bar->advance();
        echo(PHP_EOL);

        // set up the new directory.

        if (!(file_exists($packages_path))) {
            $this->info('Creating packages directory...');
            Ana::create_directory($packages_path, $perms = 0777);
        }
        $bar->advance();
        echo(PHP_EOL);

        if (!file_exists($packages_path . '/' . $vendor)) {
            $this->info('Creating vendor...');
            Ana::create_directory($packages_path . '/' . $vendor, $perms = 0777);
        }
        $bar->advance();
        echo(PHP_EOL);

        if (file_exists($packages_path . '/' . $vendor . '/' . $package_name)) {
            Ana::error_out('Sorry, can\'t help you. That package directory already exists.');
        } else {
            $installation_dir = $packages_path . '/' . $vendor;
        }
        $bar->advance();
        echo(PHP_EOL);

        // Now we have a place to store things.
        $this->info('Downloading skeleton ...');
        Ana::execute('git clone http://github.com/imonroe/skeleton.git '.$packages_path . '/' . $vendor . '/' . $package_name);
        $bar->advance();
        echo(PHP_EOL);
        
        $this->info('Beginning replacements.');

        $replacements = [
            ':vendor' => $vendor,
            ':package_name' => $package_name,
            ':package_description' => $package_description,
            ':author_name' => $author_name,
            ':author_email' => $author_email,
            ':author_website' => 'https://github.com/'.$author_github,
            ':author_username' => $author_github,
            'namespace imonroe\skeleton;' => 'namespace '.$vendor.'\\'.$package_name.';',
            'namespace imonroe\skeleton\Http\Controllers;' => 'namespace '.$vendor.'\\'.$package_name.'\Http\Controllers;',
            'SkeletonAspect' => Ana::code_safe_name($package_name).'Aspect',
            'SkeletonController' => Ana::code_safe_name($package_name).'Controller',
            'SkeletonTest' => Ana::code_safe_name($package_name).'Test',
            'SkeletonServiceProvider' => $package_name.'ServiceProvider',
            "(__DIR__.'/../reources/views', 'skeleton');" => "(__DIR__.'/../reources/views', '".$package_name."');",
            "resource_path('views/imonroe/skeleton')," => "resource_path('views/".$vendor."/".$package_name."'),",
        ];

        $files_to_check = [
            'README.md',
            'CHANGELOG.md',
            'CONTRIBUTING.md',
            'LICENSE.md',
            'composer.json',
            'tests/SkeletonTest.php',
            'src/SkeletonAspect.php',
            'src/SkeletonServiceProvider.php',
            'src/Http/routes.php',
            'src/Http/Controllers/SkeletonController.php',
        ];

        foreach ($files_to_check as $file) {
            foreach ($replacements as $find => $replace) {
                Ana::replace_and_save($installation_dir.'/'.$package_name.'/'.$file, $find, $replace);
            }
            $bar->advance();
            echo(PHP_EOL);
        }

        $this->info('Finished the replacements.'.PHP_EOL);

        $files_to_rename = [
            'tests/SkeletonTest.php' => 'tests/' . Ana::code_safe_name($package_name) .'Test.php',
            'src/SkeletonAspect.php' => 'src/' . Ana::code_safe_name($package_name) .'Aspect.php',
            'src/SkeletonServiceProvider.php' => 'src/' . $package_name .'ServiceProvider.php',
            'src/Http/Controllers/SkeletonController.php' => 'src/Http/Controllers/' . Ana::code_safe_name($package_name) .'Controller.php',
        ];

        $this->info('Renaming skeleton files.'.PHP_EOL);
        foreach ($files_to_rename as $old => $new) {
            Ana::execute('mv '.$installation_dir.'/'.$package_name.'/'.$old.' '.$installation_dir.'/'.$package_name.'/'.$new);
            $bar->advance();
            echo(PHP_EOL);
        }
        $this->info('Finished renaming files.'.PHP_EOL);
        $bar->advance();
        echo(PHP_EOL);
        $this->info('Removing existing git information.');
        Ana::execute('rm -rf '.$installation_dir.'/'.$package_name.'/.git');
        $bar->advance();
        echo(PHP_EOL);
        
        $this->info('Updating your composer.json file.');
        $composer_replacement = '"psr-4": {'.PHP_EOL.'"'.$vendor.'\\\\'.$package_name.'\\\\": "packages/'.$vendor.'/'.$package_name.'/src",';
        //Ana::dd($composer_replacement);
        Ana::replace_and_save('composer.json', '"psr-4": {', $composer_replacement);
        $this->info('Composer.json file updated.');
        $bar->advance();
        echo(PHP_EOL);

        $this->info('Updating config/app.php.');
        $app_replacement = 'imonroe\crps\crpsServiceProvider::class,'.PHP_EOL.$vendor.'\\'.$package_name.'\\'. $package_name .'ServiceProvider::class,';
        Ana::replace_and_save('config/app.php', 'imonroe\crps\crpsServiceProvider::class,', $app_replacement);
        $this->info('Configuration updated.');
        $bar->advance();
        echo(PHP_EOL);

        $this->info('Rebuilding autoloader.');
        Ana::execute('composer dump-autoload');
        $bar->advance();
        echo(PHP_EOL);
        $bar->finish();
        echo(PHP_EOL);
        $this->info('Complete.'.PHP_EOL);
        $this->info('Thank you for building with Coldreader. Your new package is ready to work on in: '.$installation_dir.'/'.$package_name.PHP_EOL);
        exit();
    }
}
