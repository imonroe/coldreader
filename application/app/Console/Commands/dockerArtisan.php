<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use imonroe\ana\Ana;

class dockerArtisan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docker:artisan-migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'run artisan:migrate in a docker container.';

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
        $php_container = ( !empty( env('DOCKER_PHP_CONTAINER') ) ) ? env('DOCKER_PHP_CONTAINER') : 'php';
        Ana::execute('docker exec -it -w /code php php artisan migrate');
    }
}
