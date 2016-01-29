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
$action = NULL;
if (isset ( $_POST ['action'] )) {
	$action = trim ( $_POST ['action'] );
}

if ((isset ( $action ))) {
	
	switch ($action) {
		
		case "list_subject_types" :
			$current_taxonomy = new Taxonomy ();
			if (isset ( $_POST ['id'] )) {
				$current_taxonomy->load ( ( int ) $_POST ['id'] );
			} else {
				$current_taxonomy->load ();
			}
			echo '<p><a href="index.php?p=form_add_subject_type">Add a new subject type</a></p>';
			echo $current_taxonomy->get_taxonomy ();
			break;
		
		case "list_subject_types_DEPRECATED" :
			// might have an ID value passed as well.
			$db = Database::get_instance ();
			$query = $db->prepare ( "SELECT id FROM subject_types" );
			if ($query->execute ()) {
				$subjects_collection = array ();
				while ( $row = $query->fetch () ) {
					$new_subject = new SubjectType ();
					$new_subject->load ( $row ['id'] );
					$subjects_collection [] = $new_subject;
				}
				$output = '<table class="table table-striped table-bordered">';
				$output .= '<thead><tr><td><strong>Subject types</strong></td><td><strong>Description</strong></td></tr></thead>';
				foreach ( $subjects_collection as $subject ) {
					$output .= '<tr><td>';
					$output .= '<a href="index.php?p=list_subjects&constraint=subject_type&id=' . $subject->id . '">' . $subject->type_name . '</a>';
					$output .= '</td>';
					$output .= '<td>' . $subject->type_description . '</td>';
					$output .= '</tr>';
				}
				$output .= '</table>';
				echo $output;
			} else {
				echo "false";
			}
			break;
		
		case "new_subject_type" :
			$new_subject_type = new SubjectType ();
			$new_subject_type->type_name = $_POST ['type_name'];
			$new_subject_type->type_description = $_POST ['type_description'];
			$new_subject_type->aspect_group = $_POST ['aspect_group'];
			if (( int ) $_POST ['parent_id'] > 0) {
				$new_subject_type->parent_id = ( int ) $_POST ['parent_id'];
			}
			$new_subject_type->save ();
			echo "successfully saved.";
			break;
		
		default :
			new LogEntry ( __FILE__ . " was hit with an invalid action, from IP: " . $_SERVER ['HTTP_X_FORWARDED_FOR'] );
			echo ('There was an error.  It has been logged.');
	}
} else {
	new LogEntry ( __FILE__ . " was hit with no action, from IP: " . $_SERVER ['HTTP_X_FORWARDED_FOR'] );
	;
	echo ('There was an error.  It has been logged.');
}

?>