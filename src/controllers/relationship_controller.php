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
		case "add_new_relationship" :
			// do something.
			$subject_1_name = $_POST ['subject_1_name'];
			$subject_2_name = $_POST ['subject_2_name'];
			$description = $_POST ['relationship_description'];
			$subject_1 = new Subject ();
			$subject_1->load_from_name ( $subject_1_name );
			$subject_2 = new Subject ();
			$subject_2->load_from_name ( $subject_2_name );
			$new_rel = new Relationship ();
			$new_rel->subject_id_1 = $subject_1->id;
			$new_rel->subject_id_2 = $subject_2->id;
			$new_rel->description = $description;
			if ($new_rel->save ()) {
				return true;
			} else {
				return false;
			}
			break;
		
		case "autocomplete" :
			$a_json = array ();
			$a_json_row = array ();
			$db = Database::get_instance ();
			$query = $db->prepare ( "SELECT description FROM relationships WHERE description LIKE ? ORDER BY description" );
			$data = array (
					'%' . $term . '%' 
			);
			if ($query->execute ( $data )) {
				while ( $row = $query->fetch () ) {
					$a_json_row ["value"] = $row ['description'];
					$a_json_row ["label"] = $row ['description'];
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