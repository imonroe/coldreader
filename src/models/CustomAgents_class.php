<?php

/**
 * Coldreader 
 *
 * PHP version 5
 *
 * LICENSE: There's plenty of third-party libs in use, 
 * and nothing here should be interpreted to change or 
 * contradict anything that is stipulated in the licenses 
 * for those components.  As for my code, it's Creative 
 * Commons Attribution-NonCommercial-ShareAlike 3.0 
 * United States. (http://creativecommons.org/licenses/by-nc-sa/3.0/us/).  
 * For more information, contact Ian Monroe: ian@ianmonroe.com
 *
 * @author     Ian Monroe <ian@ianmonroe.com>
 * @copyright  2016
 * @version    0.1 ALPHA UNSTABLE
 * @link       http://www.ianmonroe.com
 * @since      File included in initial release
 *
 */
class FullContactAgent {
	public $api_key;
	public $api_url;
	public $subject_id;
	public $email;
	public $result;
	public $aspect_type_id;
	public function _construct() {
		$this->aspect_type_id = '17';
	}
	public function _destruct() {
	}
	public function query() {
		$this->api_key = '5575816ce2e8c336';
		$this->api_url = 'https://api.fullcontact.com/v2/person.json';
		$query_construction = $this->api_url . '?email=' . $this->email . '&apiKey=' . $this->api_key;
		echo ($query_construction);
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $query_construction );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 0 );
		$this->result = curl_exec ( $curl );
		curl_close ( $curl );
		echo ($this->result);
	}
	public function write_aspect() {
		$new_aspect = new Aspect ();
		$new_aspect->aspect_type = '17';
		$new_aspect->aspect_data = $this->result;
		$new_aspect->aspect_source = 'FullContact API results';
		$new_aspect->markdown = 0;
		$new_aspect->is_hidden = 1;
		$new_aspect->save ();
		$new_subject = new Subject ();
		$new_subject->load ( $this->subject_id );
		$new_subject->add_aspect ( $new_aspect );
	}
} // end FullContactAgent class.
class AylienAgent extends Agent {
	public function __construct() {
		$app = App::get_instance ();
		$this->api_key = $app ['aylien'] ['api_key'];
		$this->api_app = $app ['aylien'] ['app_id'];
		$this->api_endpoint = $app ['aylien'] ['endpoint'];
	}
	public function query() {
	}
	public function extract_entities($text) {
		$parameters = array (
				'text' => $text 
		);
		$curl = curl_init ( 'https://api.aylien.com/api/v1/entities' );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
				'Accept: application/json',
				'X-AYLIEN-TextAPI-Application-Key: ' . $this->api_key,
				'X-AYLIEN-TextAPI-Application-ID: ' . $this->api_app 
		) );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $parameters );
		$response = curl_exec ( $curl );
		$this->result = $response;
	}
	public function write_aspect() {
		$new_aspect = AspectFactory::create ( 'AylienEntities', $this->result );
		$new_aspect->aspect_source = 'Aylien Entity Extraction';
		$new_aspect->is_hidden = 1;
		$new_aspect->save ();
		$new_subject = new Subject ();
		$new_subject->load ( $this->subject_id );
		$new_subject->add_aspect ( $new_aspect );
	}
} // end AylienAgent class
class WikiAgent {
	public $api_key;
	public $api_url;
	public $subject_id;
	public $query_string;
	public $result;
	public $aspect_type_id;
	public function _construct() {
		$this->aspect_type_id = '17';
	}
	public function _destruct() {
	}
	public function query() {
		$this->api_url = 'http://en.wikipedia.org/w/api.php';
		$query_construction = $this->api_url . '?action=query&titles=' . urlencode ( $this->query_string ) . '&prop=revisions&rvprop=content&format=json';
		echo ($query_construction);
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $query_construction );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 0 );
		$app = App::get_instance ();
		curl_setopt ( $curl, CURLOPT_USERAGENT, $app ['user-agent'] );
		$this->result = curl_exec ( $curl );
		curl_close ( $curl );
		echo ($this->result);
	}
	public function write_aspect() {
		$new_aspect = new Aspect ();
		$new_aspect->aspect_type = '';
		$new_aspect->aspect_data = $this->result;
		$new_aspect->aspect_source = 'Wikipedia API results';
		$new_aspect->markdown = 0;
		$new_aspect->is_hidden = 1;
		$new_aspect->save ();
		$new_subject = new Subject ();
		$new_subject->load ( $this->subject_id );
		$new_subject->add_aspect ( $new_aspect );
		$new_subject->update ();
	}
} // end WikiAgent class.
class DuckAgent extends Agent {
	public function __construct() {
		$app = App::get_instance ();
		$this->api_key = $app ['mashape'] ['api_key'];
		$this->api_endpoint = 'https://duckduckgo-duckduckgo-zero-click-info.p.mashape.com/';
	}
	public function query($s = '') {
		if ($s != '') {
			$this->search_terms = $s;
		}
		$query_construction = $this->api_endpoint . '?format=json&no_html=1&no_redirect=1&q=' . urlencode ( $this->search_terms ) . '&skip_disambig=1';
		// echo($query_construction);
		$opts = array (
				'Accept: application/json',
				'X-Mashape-Key: ' . $this->api_key 
		);
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $query_construction );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 0 );
		$app = App::get_instance ();
		curl_setopt ( $curl, CURLOPT_USERAGENT, $app ['user-agent'] );
		curl_setopt ( $curl, CURLOPT_HTTPHEADER, $opts );
		$this->result = curl_exec ( $curl );
		curl_close ( $curl );
	}
}
class GoogleTasksAgent {
	public $service;
	public $task_lists;
	public function __construct() {
		$this->task_lists = array ();
		$app = App::get_instance ();
		$this->service = new Google_Service_Tasks ( $app ['google'] ['client'] );
		$taskLists = $this->service->tasklists->listTasklists ();
		foreach ( $taskLists->getItems () as $taskList ) {
			$this->task_lists [] = array (
					$taskList->getId,
					$taskList->getTitle () 
			);
		}
	}
	public function __destruct() {
	}
	public function print_task_lists() {
		$output = 'Task lists: ';
		$output .= '<ul>';
		foreach ( $this->task_lists as $l ) {
			// $output .= '<li>'.$l['title'].'</li>';
		}
		$output .= '</ul>';
		$output = print_r ( $this->task_lists );
		
		return $output;
	}
}

?>