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
	$new_aspect_group = new AspectGroup ();
	switch ($action) {
		case "new_aspect_group" :
			$new_aspect_group->group_name = $_POST ['group_name'];
			$new_aspect_group->save ();
			echo "new aspect group added.";
			break;
		
		case "view_aspect_group" :
			$new_aspect_group->load ( $_POST ['id'] );
			$output = '<h2>Viewing ' . $new_aspect_group->group_name . ' aspects</h2>';
			$output .= '<ul>';
			foreach ( $new_aspect_group->aspects as $as ) {
				$output .= '<li>' . $as->aspect_name . '</li>';
			}
			$output .= '</ul>';
			echo $output;
			break;
		
		case "add_type_to_group" :
			$new_aspect_group->load ( $_POST ['aspect_group_id'] );
			$new_aspect_group->quick_add_aspect_type ( $_POST ['aspect_type_id'] );
			break;
		
		case "remove_type_from_group" :
			$new_aspect_group->load ( $_POST ['aspect_group_id'] );
			$new_aspect_group->quick_remove_aspect_type ( $_POST ['aspect_type_id'] );
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