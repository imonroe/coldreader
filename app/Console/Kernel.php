<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use imonroe\crps\Aspect;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule){
		$schedule->call(function () {
			Log::info('Beginning Parse Loop');
            $aspects = Aspect::all();
			foreach ($aspects as $aspect){
				try{
					$aspect->parse();
				} catch (\Exception $e){
					Log::error('Problem with parse function in Aspect '.$aspect->id . ', could not parse. Message: '.$e->getMessage());
				}
			}
			Log::info('Completed Parse Loop');
        })->name('parse_loop')->everyFiveMinutes()->withoutOverlapping();

		$schedule->call(function(){
			Log::info('Cleaning up orphaned Aspects');
			$deleted = \DB::delete('DELETE FROM aspects WHERE id NOT IN (SELECT DISTINCT aspect_id FROM aspect_subject)');
			Log::info('Finished orphan cleanup.  Deleted '.$deleted.' orphaned aspects from the database');
		})->name('orphan_cleanup')->daily()->withoutOverlapping();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
