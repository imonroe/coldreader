<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\Jobs\UsersAspectsJob;

class UsersAspectsJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:aspects:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Enqueue Jobs for all Users' Aspects";

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
        $this->info("Enqueueing UsersAspectsJobs...\n");
        $this->output->progressStart(User::all()->count());
        $users = User::chunk(100, function ($users) {
            foreach ($users as $user) :
                UsersAspectsJob::dispatch($user);
                $this->output->progressAdvance();
            endforeach;
        });
        $this->output->progressFinish();
        $this->info("Done.");
    }
}
