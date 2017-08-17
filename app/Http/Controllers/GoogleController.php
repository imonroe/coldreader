<?php

namespace App\Http\Controllers;

use Auth;
use Socialite;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Google_Client;
use Google_Service_Tasks;
use Google_Service_Tasks_Task;
use Google_Service_Tasks_TaskLists;
use Google_Service_Tasks_Tasklists_Resource;
use Google_Service_Calendar;
use Google_Http_Request;
use Google_Service_Drive;

use imonroe\ana\Ana;
use Carbon\Carbon;
use Snoopy\Snoopy;

class GoogleController extends Controller{
	protected $client;
	protected $user;
	public function __construct(){
		// Hey, if we have an actual user request, we want to use a session variable for
		// token information.  If, however, this is an automated call, say from a parse
		// function, we will need to retrieve the user's token information from the database
		// and build a client based on that.

		if (!is_null(session('user_data'))){
			$user_data = session('user_data');
		} else {
			// You can't use the Auth:: middleware in a constructor after Laravel 5.2
			// Thus, we'll manually authorize the primary user of the app, and use the stored cookie info.
			// See: https://stackoverflow.com/questions/44445059/laravel-5-4-sessions-and-authuser-not-available-in-controllers-constructor#44445437
			$current_user = auth()->loginUsingId(1);
			$user_data = unserialize($current_user->google_token);
		}

		if (empty($user_data)){
			return redirect('/auth/google');
		}
		$google_client_token = [
			'access_token' => $user_data['token'],
			'refresh_token' => $user_data['refreshToken'],
			'expires_in' => $user_data['expiresIn'],
		];

		$client = new Google_Client();
		$client->setApplicationName(env('GOOGLE_API_APP_NAME'));
		$client->setClientId( env('GOOGLE_API_CLIENT_ID') );
		$client->setClientSecret( env('GOOGLE_API_CLIENT_SECRET') );
		$client->setDeveloperKey(env('GOOGLE_API_PUBLIC_API_KEY'));
		$client->setAccessType("offline");
		$client->setAccessToken(json_encode($google_client_token));

		if($client->isAccessTokenExpired()){
			$client->setAccessType("refresh_token");
			$client->refreshToken($google_client_token['refresh_token']);
			$new_token = $client->getAccessToken();
			$user_data['token'] = $new_token['access_token'];
			$user_data['refreshToken'] = $new_token['refresh_token'];
			$user_data['expiresIn'] = $new_token['expires_in'];
			$primary_user = \App\User::where('email', env('APP_PRIMARY_USER_EMAIL'))->first();
			$primary_user->google_token = serialize($user_data);
			$primary_user->save();
			if ( !is_null( session()->all() ) ){
				session( [ 'user_data' => $user_data ] );
			}
		}
		$this->client = $client;
	}

	// Tasks stuff.
	public function task_list($task_list_id='@default'){
		/*
		This function returns an object that looks like the following:
		Useful: $object->items[0]['title']	

		Google_Service_Tasks_Tasks {#274 ▼
			#collection_key: "items"
			+etag: ""ZPF2pw17LedTHeJNTnTTe4cmlp4/MTE2MTMxNDIyNw""
			#itemsType: "Google_Service_Tasks_Task"
			#itemsDataType: "array"
			+kind: "tasks#tasks"
			+nextPageToken: null
			#internal_gapi_mappings: []
			#modelData: array:1 [▼
			"items" => array:7 [▼
				0 => array:9 [▼
				  "kind" => "tasks#task"
				  "id" => "MDE0MTE1MjY1MjcwNzYwNDY5Nzg6MDo1NDIwNzM0NTc"
				  "etag" => ""ZPF2pw17LedTHeJNTnTTe4cmlp4/MTAyNjY0NjcwOA""
				  "title" => "Laundry"
				  "updated" => "2017-05-26T14:44:22.000Z"
				  "selfLink" => "https://www.googleapis.com/tasks/v1/lists/..."
				  "position" => "00000000000002796203"
				  "status" => "needsAction"
				  "due" => "2017-05-27T00:00:00.000Z"
				 ]
				1 => array:9 [▶]
			   ]
			]
			#processed: []
		}
		*/

		$tomorrow = strtotime('+1 day');
		$tomorrow_timestamp = date(DATE_RFC3339, $tomorrow);
		//$today_timestamp = date(DATE_RFC3339, strtotime('today 11:59PM'));
		$todo_service = new Google_Service_Tasks($this->client);
		$optParams = array(
			'maxResults' => 10,
		);
		$all_task_lists = $todo_service->tasklists->listTasklists($optParams);
		$list_params = array(
			'dueMax' => $tomorrow_timestamp,
			'showCompleted' => false					
		);
		$todo_list = $todo_service->tasks->listTasks($task_list_id, $list_params);
		return $todo_list;
	}

	public function display_task_list($task_list_id='@default'){
		if ($task_list_id == '@default'){
			$function_id = '';
		} else {
			$function_id = $task_list_id;
		}
		$output = '';
		$todo_list = $this->task_list($task_list_id);	
		foreach($todo_list->items as $t){
			$output.='<input name="'.$t['id'].'" id="'.$t['id'].'" type="checkbox" onchange="closeTodoItem_'.$function_id.'(this)" /> '.$t['title'].'<br />'.PHP_EOL;
		}
		return $output;
	}

	public function edit_task_list(Request $request){
		$today_timestamp = date(DATE_RFC3339, strtotime('today 11:59PM'));
		switch($request->input('action')){
			case "new_todo_item":
				$list_id = !empty($request->input('task_list')) ? $request->input('task_list') : '@default';
				$todo_service = new Google_Service_Tasks($this->client);
				$task = new Google_Service_Tasks_Task();
				$task->setTitle($request->input('new_task_title'));
				$task->setDue($today_timestamp);
				$result = $todo_service->tasks->insert($list_id, $task);
				echo('Got back: '.$result->getId());
				break;

			case "complete_todo_item":
				$list_id = !empty($request->input('list_id')) ? $request->input('list_id') : '@default';
				$todo_service = new Google_Service_Tasks($this->client);
				$task = $todo_service->tasks->get($list_id, $request->input('task_id') );
				$task->setStatus('completed');
				$result = $todo_service->tasks->update($list_id, $task->getId(), $task);
				break;
		}
	}
	
	public function get_all_task_lists(){
		$tasksService = new Google_Service_Tasks($this->client);
 		$tasklists = $tasksService->tasklists->listTasklists();
		return $tasklists;
	}

	// Calendar stuff.
	public function get_calendar($calendar_id='primary'){
		$calendar_service = new Google_Service_Calendar($this->client);
		$optParams = array(
			'timeMin' => Ana::google_datetime(strtotime(Carbon::now()->subMinutes(60))),
			'orderBy' => 'startTime',
			'singleEvents' => true,
			'timeMax' =>Ana::google_datetime(strtotime('tomorrow 3:00AM')),
		);
		$event_list = $calendar_service->events->listEvents($calendar_id, $optParams);
		$output = '';
		$output .= '<ul id="calendar_agenda">';
		foreach($event_list->items as $event){
			$output .=  '<li>'.$event['summary'].' - '.Ana::standard_date_format(strtotime($event['start']['dateTime'])).'</li>';
		}
		$output .=  '</ul>';
		return $output;
	}

	public function edit_calendar(Request $request){
		switch($request->input('action')){
			case "new_appointment":
				$calendar_service = new Google_Service_Calendar($this->client);
				$result = $calendar_service->events->quickAdd( 'primary', $request->input('new_appointment_txt') );
				echo('Got back: '.$result->getId());
				break;
		}
	}

	// Search stuff
	public function google_search($query){
		$cse_link = 'https://www.googleapis.com/customsearch/v1';
		$cse_link .= '?key='.env('GOOGLE_CUSTOM_SEARCH_API_KEY');
		$cse_link .= '&q='.urlencode($query);
		$cse_link .= '&cx='.env('GOOGLE_CUSTOM_SEARCH_CZ');
		$cse_link .= '&alt=json';
		$fetcher = new Snoopy;
		$fetcher->fetch($cse_link);
		$results = $fetcher->results;
		$results_array = json_decode($results, true);
		return $results_array;
	}

	// Contacts stuff
	public function get_contacts(){
		// returns JSON formatted contact list from Google.
		$request_url = 'https://www.google.com/m8/feeds/contacts/'.urlencode(env('APP_PRIMARY_USER_EMAIL')).'/full?max-results=2000&alt=json';
		return $this->get_authenticated_url($request_url);
	}

	// General functions
	public function get_authenticated_url($request_url){
		$token = $this->client->getAccessToken();
		$header = array();
		$header[] = "Content-type: application/atom+xml"; 
		$header[] = 'Authorization: OAuth '.$token['access_token'];
		$header[] = 'GData-Version: 3.0';

		$curl = curl_init();
 		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl,CURLOPT_URL, $request_url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, TRUE);
		$api_response = trim(curl_exec($curl));
		$curl_error = curl_error($curl);
		curl_close($curl);
		return $api_response;
	}

	// Maps stuff
	public static function get_static_map($address){
		// returns a hyperlinked map to a given address.
		$static_maps_endpoint = 'https://maps.googleapis.com/maps/api/staticmap?';
		$api_key = env('GOOGLE_STATIC_MAPS_API_KEY');
		$parameters = array(
			'center' => urlencode($address),
			'size' => '250x250',
			'scale' => '1',
			'format' => 'png',
			'markers' => 'color:blue%7C'.urlencode($address),
			'mapType' => 'roadmap',  // {roadmap, satellite, hybrid, or terrain}
			'zoom' => '15',  //{ 0 is fully zoomed out, 20 is fully zoomed in}
			'key' => $api_key
		);
		foreach ($parameters as $key => $value){
			$static_maps_endpoint .= $key . '=' . $value . '&';
		}
		$hyperlink = 'https://www.google.com/maps/place/'. urlencode($address);
		$output = '<a href="'.$hyperlink.'" target="_blank"><img src="'.$static_maps_endpoint.'" ></a>';
		return $output;
	}

	// Drive stuff
	public function search_drive($query=''){
		$excluded_directory = '';
		$output = array();
		$service = new Google_Service_Drive($this->client);
		$pageToken = null;
		do {
			$response = $service->files->listFiles(array(
				'q' => "name contains '".$query."' and (trashed = false)",
				'spaces' => 'drive',
				'pageToken' => $pageToken,
				'fields' => 'nextPageToken, files(id, name, webViewLink, parents)',
			));

			foreach ($response->files as $file) {
				//dd($file);
				$output[$file->getWebViewLink()] = $file->getName() . ' parents: '. var_export($file->getParents(), true); 
			}
		} while ($pageToken != null);
		return $output;
	}


}
