<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use imonroe\ana\Ana;

class ColdreaderInsaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coldreader:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs initial setup for Coldreader software.';

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
        $this->info('Thanks for trying out Coldreader!');
        Ana::say('Beginning Coldreader post-installation script');
        $dbs = Ana::ask_user('What is your database server? [127.0.0.1]');
        $dbn = Ana::ask_user('What is the name of the database? [homestead]');
        $dbu = Ana::ask_user('What is the database username? [homestead]');
        $dbp = Ana::ask_user('What is the database password? [secret]');
        $url = Ana::ask_user('What is the app url? [http://localhost]');
        $do_npm = Ana::ask_user('Install npm dependencies? y/n [n]');
        $npm = (!empty($do_npm)) ? $do_npm : 'n';
        $database_server = (!empty($dbs)) ? $dbs : '127.0.0.1';
        $database_name = (!empty($dbn)) ? $dbn : 'homestead';
        $database_user = (!empty($dbu)) ? $dbu : 'homestead';
        $database_password = (!empty($dbp)) ? $dbp : 'secret';
        $app_url = (!empty($url)) ? $url : 'http://localhost';

        Ana::say('Reading config file');
        if ( !file_exists('.env') ){
            Ana::execute('cp .env.example .env');
        }
        $environment = file('.env');

        Ana::say('Making replacements');
        foreach ($environment as $key => $value){
            if (!(strrpos($value, 'DB_HOST=')===FALSE)){
                $environment[$key] = 'DB_HOST='.$database_server . PHP_EOL;
            }
            if (!(strrpos($value, 'DB_DATABASE=')===FALSE)){
                $environment[$key] = 'DB_DATABASE='.$database_name . PHP_EOL;
            }
            if (!(strrpos($value, 'DB_USERNAME=')===FALSE)){
                $environment[$key] = 'DB_USERNAME='.$database_user . PHP_EOL;
            }
            if (!(strrpos($value, 'DB_PASSWORD=')===FALSE)){
                $environment[$key] = 'DB_PASSWORD='.$database_password . PHP_EOL;
            }
            if (!(strrpos($value, 'APP_NAME=')===FALSE)){
                $environment[$key] = 'APP_NAME=Coldreader' . PHP_EOL;
            }
            if (!(strrpos($value, 'APP_URL=')===FALSE)){
                $environment[$key] = 'APP_URL='. $app_url . PHP_EOL;
            }
        }
        $env = implode('', $environment);
        Ana::say('Writing file');
        file_put_contents('.env', $env);
        Ana::say('File written');

        //Ana::execute('composer install');
        Ana::execute('php artisan key:generate');

        Ana::execute('php artisan migrate');
        $is_linked = Ana::execute('php artisan storage:link');
        Ana::say('The return value on that was: '.$is_linked);
        if ($npm == 'y'){
            Ana::execute('npm install');
            Ana::execute('npm run production');
        }
        
        Ana::say('Completed post-installation script.  Enjoy the software!');
        exit();

    }
}
