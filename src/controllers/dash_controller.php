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
csfr_protection ();
$db = Database::get_instance ();
$action = NULL;
if (isset ( $_POST ['action'] )) {
	$action = trim ( $_POST ['action'] );
}

if ((isset ( $action ))) {
	switch ($action) {
		case "view_dash" :
			break;
		
		case "inspire_me" :
			$sayings = new Subject ();
			$sayings->load_from_name ( 'Inspirations' );
			$sayings_array = $sayings->to_array ();
			$output = $sayings_array ['aspects'] [array_rand ( $sayings_array ['aspects'] )] ['aspect_data'];
			// print_r($inspirational_sayings);
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