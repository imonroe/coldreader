<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
use imonroe\crps\Aspect;
use imonroe\crps\AspectFactory;
use imonroe\crps\Subject;
use imonroe\crps\Ana;
use Validator;

class DefaultAspect extends Aspect{

    function __construct()
    {
		parent::__construct();
	}

    public function notes_schema()
    {
		$settings = json_decode(parent::notes_schema(), true);
		return json_encode($settings);
	}

    public function create_form($subject_id, $aspect_type_id=null)
    {
		return parent::create_form($subject_id, $this->aspect_type);
	}

    public function edit_form()
    {
		return parent::edit_form();
	}

    public function display_aspect()
    {
        $parent_output = parent::display_aspect();
        $new_output = '<p>Some markup.</p>';
		return $parent_output . $new_output;
	}

    public function parse()
    {
        return parent::parse();
		//if (empty($this->last_parsed) || strtotime($this->last_parsed) < strtotime('now -1 hour') ){
			// do something?
		//}
		//$this->last_parsed = Carbon::now();
		//$this->update_aspect();
    }
    
    public function pre_save(Request &$request)
    {
        return false;
    }
    public function post_save(Request &$request)
    {
        return false;
    }
    public function pre_update(Request &$request)
    {
        return false;
    }
    public function post_update(Request &$request)
    {
        return false;
    }
    public function pre_delete(Request &$request)
    {
        return false;
    }

}
