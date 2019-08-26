<?php

namespace imonroe\timezone_agent;

class TimezoneAgent
{

    protected $current_timezone;
    protected $script_location;

    /**
     * Create a new Instance
     */
    public function __construct()
    {
        $this->current_timezone = config('app.timezone');
    }

    public function get(){
        return $this->current_timezone;
    }

    public function set($tz_string){
        if ( !empty($tz_string) ){
            config(['app.timezone' => $tz_string]);
        } else {
            throw \Exception('Cannot set timezone to an empty string');
        }
    }


}
