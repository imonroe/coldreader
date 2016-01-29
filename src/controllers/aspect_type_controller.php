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
if (isset ( $_GET ['term'] )) {
	$action = 'autocomplete';
	$term = $_GET ['term'];
}

if ((isset ( $action ))) {
	switch ($action) {
		case "autocomplete" :
			$a_json = array ();
			$a_json_row = array ();
			$db = Database::get_instance ();
			$query = $db->prepare ( "SELECT id, aspect_name FROM aspect_types WHERE aspect_name LIKE ? ORDER BY aspect_name" );
			$data = array (
					'%' . $term . '%' 
			);
			if ($query->execute ( $data )) {
				while ( $row = $query->fetch () ) {
					$a_json_row ["id"] = $row ['id'];
					$a_json_row ["value"] = $row ['aspect_name'];
					$a_json_row ["label"] = $row ['aspect_name'];
					array_push ( $a_json, $a_json_row );
				}
			}
			echo json_encode ( $a_json );
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