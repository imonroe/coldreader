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

// bootstrap the rest of the codebase.
require_once ('../config.php');

// echo "GOT IN GOOGLE API CONTROLLER";
csfr_protection ();
$db = Database::get_instance ();
$app = App::get_instance ();
$action = NULL;
if (isset ( $_POST ['action'] )) {
	$action = trim ( $_POST ['action'] );
}

if ((isset ( $action ))) {
	switch ($action) {
		
		case "view_todo" :
			if (isset ( $_POST ['task_list_id'] )) {
				$task_list_id = $_POST ['task_list_id'];
			} else {
				$task_list_id = '@default';
			}
			$output = '';
			$tomorrow = strtotime ( '+1 day' );
			$tomorrow_timestamp = date ( DATE_RFC3339, $tomorrow );
			$today_timestamp = date ( DATE_RFC3339, strtotime ( 'today 11:59PM' ) );
			$todo_service = new Google_Service_Tasks ( $APP ['google'] ['client'] );
			$optParams = array (
					'maxResults' => 10 
			);
			$all_task_lists = $todo_service->tasklists->listTasklists ( $optParams );
			$list_params = array (
					'dueMax' => $tomorrow_timestamp,
					'showCompleted' => false 
			);
			$todo_list = $todo_service->tasks->listTasks ( $task_list_id, $list_params );
			foreach ( $todo_list->items as $t ) {
				$output .= '<input name="' . $t ['id'] . '" id="' . $t ['id'] . '" type="checkbox" onchange="closeTodoItem(this)" /> ' . $t ['title'] . '<br />' . PHP_EOL;
			}
			echo $output;
			break;
		
		case "view_shopping_list" :
			$output = '';
			$todo_service = new Google_Service_Tasks ( $APP ['google'] ['client'] );
			$list_params = array (
					'showCompleted' => false 
			);
			$shopping_list = $todo_service->tasks->listTasks ( 'XXXXXXXXXXXXXXXXXXXXXX', $list_params );
			foreach ( $shopping_list->items as $t ) {
				$output .= '<input name="' . $t ['id'] . '" id="' . $t ['id'] . '" type="checkbox" onchange="closeShoppingItem(this)" /> ' . $t ['title'] . '<br />' . PHP_EOL;
			}
			echo $output;
			break;
		
		case "new_todo_item" :
			$todo_service = new Google_Service_Tasks ( $APP ['google'] ['client'] );
			$task = new Google_Service_Tasks_Task ();
			$task->setTitle ( $_POST ['new_task_title'] );
			$task->setDue ( $_POST ['due'] );
			$result = $todo_service->tasks->insert ( $_POST ['task_list'], $task );
			echo ('Got back: ' . $result->getId ());
			break;
		
		case "new_shopping_item" :
			$todo_service = new Google_Service_Tasks ( $APP ['google'] ['client'] );
			$task = new Google_Service_Tasks_Task ();
			$task->setTitle ( $_POST ['new_task_title'] );
			$task->setDue ( $_POST ['due'] );
			$result = $todo_service->tasks->insert ( $_POST ['task_list'], $task );
			echo ('Got back: ' . $result->getId ());
			break;
		
		case "complete_todo_item" :
			$todo_service = new Google_Service_Tasks ( $APP ['google'] ['client'] );
			$task = $todo_service->tasks->get ( '@default', $_POST ['task_id'] );
			$task->setStatus ( 'completed' );
			$result = $todo_service->tasks->update ( '@default', $task->getId (), $task );
			break;
		
		case "complete_shopping_item" :
			$todo_service = new Google_Service_Tasks ( $APP ['google'] ['client'] );
			$task = $todo_service->tasks->get ( 'XXXXXXXXXXXXXX', $_POST ['task_id'] );
			$task->setStatus ( 'completed' );
			$result = $todo_service->tasks->update ( 'XXXXXXXXXXXXXXXXX', $task->getId (), $task );
			break;
		
		case "view_calendar" :
			$calendar_service = new Google_Service_Calendar ( $APP ['google'] ['client'] );
			$optParams = array (
					'timeMin' => $APP ['ana']->google_datetime ( strtotime ( "now -1 hour" ) ),
					'orderBy' => 'startTime',
					'singleEvents' => true,
					'timeMax' => $APP ['ana']->google_datetime ( strtotime ( "Today 11:59PM" ) ) 
			);
			$event_list = $calendar_service->events->listEvents ( 'primary', $optParams );
			echo '<ul id="calendar_agenda">';
			foreach ( $event_list->items as $event ) {
				echo '<li>' . $event ['summary'] . ' - ' . $APP ['ana']->standard_date_format ( strtotime ( $event ['start'] ['dateTime'] ) ) . '</li>';
			}
			echo '</ul>';
			break;
		
		case "new_appointment" :
			$calendar_service = new Google_Service_Calendar ( $APP ['google'] ['client'] );
			$result = $calendar_service->events->quickAdd ( 'primary', $_POST ['new_appointment_txt'] );
			echo ('Got back: ' . $result->getId ());
			break;
		
		case "google_search" :
			$api_key = $APP ['google'] ['custom_search'] ['api_key'];
			$search_terms = str_replace ( " ", "+", $_POST ['query'] );
			$cse_link = 'https://www.googleapis.com/customsearch/v1';
			$cse_link .= '?key=' . $api_key;
			$cse_link .= '&q=' . $search_terms;
			$cse_link .= '&cx=' . $APP ['google'] ['custom_search'] ['cz'];
			$cse_link .= '&alt=json';
			$fetcher = new Snoopy ();
			$fetcher->fetch ( $cse_link );
			$results = $fetcher->results;
			$results_array = json_decode ( $results, true );
			$output = '<h3>Top results from Google</h3>';
			$output .= '<ul>';
			foreach ( $results_array ['items'] as $result ) {
				$output .= '<li><a href="' . $result ['link'] . '">' . $result ['title'] . '</a> - ' . $result ['displayLink'] . '</li>';
			}
			$output .= '</ul>';
			echo $output;
			break;
		
		case "google_drive_search" :
			// yo, remember, the PHP lib uses V2 of the API, not V3, which seems to be the default in the examples.
			$result = array ();
			$output = '<h3>Google Drive Results</h3>' . PHP_EOL;
			$output .= '<ul>' . PHP_EOL;
			$drive_service = new Google_Service_Drive ( $APP ['google'] ['client'] );
			$search_terms = $_POST ['query'];
			$pageToken = null;
			do {
				try {
					$parameters = array ();
					$parameters ['q'] = "title contains '" . $search_terms . "'";
					// only for full text search
					// $parameters['q'] .= " or fullText contains '".$search_terms."'";
					$parameters ['maxResults'] = 100;
					if ($pageToken) {
						$parameters ['pageToken'] = $pageToken;
					}
					$files = $drive_service->files->listFiles ( $parameters );
					
					$result = array_merge ( $result, $files->getItems () );
					$pageToken = $files->getNextPageToken ();
				} catch ( Exception $e ) {
					print "An error occurred: " . $e->getMessage ();
					$pageToken = NULL;
				}
			} while ( $pageToken );
			
			foreach ( $files as $file ) {
				$output .= '<li>';
				$output .= '<a href="' . $file->alternateLink . '" target="_blank">' . $file->title . '</a>';
				$output .= '</li>' . PHP_EOL;
			}
			
			$output .= '</ul>' . PHP_EOL;
			echo $output;
			break;
		
		default :
			new LogEntry ( __FILE__ . " was hit with an invalid action, from IP: " . $_SERVER ['REMOTE_ADDR'] );
			echo ('There was an error.  It has been logged.');
	}
} else {
	new LogEntry ( __FILE__ . " was hit with no action, from IP: " . $_SERVER ['REMOTE_ADDR'] );
	;
	echo ('There was an error.  It has been logged.');
}

?>