<?php

// swiped from: https://gist.github.com/davidpiesse/be9db81995b45238a9008c1dcc4c25fd
// More info: https://laravel-news.com/user-defined-schedules-in-laravel

//Don't forget to change the namespace!
namespace App\Traits;
use Cron\CronExpression;
use Illuminate\Support\Carbon;
use Illuminate\Console\Scheduling\ManagesFrequencies;
trait Schedulable{
    use ManagesFrequencies;
    protected $expression = '* * * * *';
    protected $timezone;
    public function isDue(){
        $date = Carbon::now();
        if ($this->timezone) {
            $date->setTimezone($this->timezone);
        }
        return CronExpression::factory($this->expression)->isDue($date->toDateTimeString());
    }
    public function nextDue(){
        return Carbon::instance(CronExpression::factory($this->expression)->getNextRunDate());
    }
    public function lastDue(){
        return Carbon::instance(CronExpression::factory($this->expression)->getPreviousRunDate());
    }
}
