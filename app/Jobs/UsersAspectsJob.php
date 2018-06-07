<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\User;
use imonroe\crps\Subject;
use imonroe\crps\Aspect;
use imonroe\crps\AspectCollection;
use imonroe\crps\AspectFactory;
use App\Jobs\ParseAspectJob;

class UsersAspectsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("UsersAspectJob for User#{$this->user->id} Start.");
        try {
            Auth::loginUsingId($this->user->id, true);
            $aspects = Aspect::where('user', '=', $this->user->id)->get();
            Log::info('I count: '.$aspects->count() .' Aspects for user:'.$this->user->id);
            foreach ($aspects as $aspect) {
                Log::info('This one looks like a '.get_class($aspect));
                ParseAspectJob::dispatch($aspect);
                Log::info("Enqueued ParseAspectJob for Aspect# ".$aspect->id);
            }
        } catch (Exception $e) {
            Log::info(var_export($e, true));
        }
        Log::info("UsersAspectJob for User# " .$this->user->id. " End.");
    }
}
