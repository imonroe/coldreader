<?php

namespace imonroe\cr_network_aspects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use imonroe\crps\Aspect;
use imonroe\crps\Ana;

class RSSFeedAspect extends WebpageAspect{

	protected $feed;

	function __construct(){
		parent::__construct();
	}

	public function notes_schema(){
		$settings = json_decode(parent::notes_schema(), true);
		$settings['number_of_items'] = '5';
		return json_encode($settings);
	}

	public function create_form($subject_id, $aspect_type_id=null){
		return parent::create_form($subject_id, $this->aspect_type);
	}

	public function edit_form(){
		return parent::edit_form($id);
	}

	public function display_aspect(){
		$settings = (!is_null($this->aspect_notes)) ? json_decode($this->aspect_notes, true) : json_decode($this->notes_schema(), true);
		$feed_url = trim($this->aspect_source);
		$item_count = (!empty($settings['number_of_items'])) ? $settings['number_of_items'] : '1'; 
		$feed_options = [
			'feed_url' => $feed_url,
			'item_count' => $item_count,
		];
		$output = '<rss-aspect-display options="'.htmlspecialchars(json_encode($feed_options)).'"></rss-aspect-display>';
		return $output;
	}

	public function parse(){
	  //Log::info('entered parse function for Aspect: '.$this->id);
		//if (empty($this->last_parsed) || strtotime($this->last_parsed) < strtotime('now -1 hour') ){
			// do something?
		//}
		//$this->last_parsed = Carbon::now();
		//$this->update_aspect();
	}
}  // End of the RSSFeedAspectclass.
