<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

use imonroe\crps\Aspect;

class ParseAspectJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $aspect;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Aspect $aspect)
    {
        $this->aspect = $aspect;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->aspect->parse();
        Log::info("Parsed Aspect: " . $this->aspect->id);
    }
}
