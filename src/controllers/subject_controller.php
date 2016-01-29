<?php
ob_end_clean ();
/**
 * Coldreader
 *
 * PHP version 5
 *
 * LICENSE: There's plenty of third-party libs in use,
 * and nothing here should be interpreted to change or
 * contradict anything that is stipulated in the licenses
 * for those components. As for my code, it's Creative
 * Commons Attribution-NonCommercial-ShareAlike 3.0
 * United States. (http://creativecommons.org/licenses/by-nc-sa/3.0/us/).
 * For more information, contact Ian Monroe: ian@ianmonroe.com
 *
 * @author Ian Monroe <ian@ianmonroe.com>
 * @copyright 2016
 * @version 0.1 ALPHA UNSTABLE
 * @link http://www.ianmonroe.com
 * @since File included in initial release
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
		case "list_subjects" :
			$constraint = false;
			$id = false;
			if (isset ( $_POST ['constraint'] )) {
				$constraint = trim ( $_POST ['constraint'] );
			}
			if (isset ( $_POST ['id'] )) {
				$id = trim ( $_POST ['id'] );
			}
			
			$sql = "SELECT id FROM subjects";
			if ($constraint) {
				$sql .= " WHERE " . $constraint;
			}
			if ($id) {
				$sql .= "'" . ( int ) $id . "'";
			}
			
			// build the presentation output.
			$query = $db->prepare ( $sql );
			if ($query->execute ()) {
				$subjects_collection = array ();
				
				while ( $row = $query->fetch () ) {
					$new_subject = new Subject ();
					$new_subject->load ( $row ['id'] );
					$subjects_collection [] = $new_subject;
				}
				$output = '<table class="table table-striped table-bordered">';
				$output .= '<thead><tr><td><strong>Subjects</strong></td></tr></thead>';
				foreach ( $subjects_collection as $subject ) {
					$output .= '<tr><td>';
					$output .= '<a href="index.php?p=subject_view&id=' . $subject->id . '">' . $subject->name . '</a>';
					$output .= '</td></tr>';
				}
				$output .= '</table>';
				echo $output;
			} else {
				echo "false";
			}
			// end presentation output;
			break;
		
		case "list_subjects_of_type" :
			if (isset ( $_POST ['id'] )) {
				// This is our SubjectTypeID
				$id = trim ( $_POST ['id'] );
				$current_subject_type = new SubjectType ();
				$current_subject_type->load ( $id );
				$current_subject_type->load_children ();
				$sql = "SELECT id FROM subjects WHERE subject_type IN (";
				$sql .= $id;
				foreach ( $current_subject_type->children as $child ) {
					$sql .= ', ' . $child->id;
				}
				$sql .= ')';
				if ($APP ['debug']) {
					var_dump ( $sql );
				}
				// build the presentation output.
				$query = $db->prepare ( $sql );
				if ($query->execute ()) {
					$subjects_collection = array ();
					
					while ( $row = $query->fetch () ) {
						$new_subject = new Subject ();
						$new_subject->load ( $row ['id'] );
						$subjects_collection [] = $new_subject;
					}
					$output = '<table class="table table-striped table-bordered">';
					$output .= '<thead><tr><td><strong>Subjects</strong></td></tr></thead>';
					foreach ( $subjects_collection as $subject ) {
						$output .= '<tr><td>';
						$output .= '<a href="index.php?p=subject_view&id=' . $subject->id . '">' . $subject->name . '</a>';
						$output .= '</td></tr>';
					}
					$output .= '</table>';
					echo $output;
				} else {
					echo "false";
				}
				// end presentation output;
			}
			break;
		
		case "view_subject" :
			$constraint = false;
			$id = false;
			if (isset ( $_POST ['constraint'] )) {
				$constraint = trim ( $_POST ['constraint'] );
			}
			if (isset ( $_POST ['id'] )) {
				$id = ( int ) trim ( $_POST ['id'] );
			}
			$sql = "SELECT id FROM subjects WHERE id=?";
			$data = array (
					$id 
			);
			$query = $db->prepare ( $sql );
			if ($query->execute ( $data )) {
				while ( $row = $query->fetch () ) {
					$current_subject = new Subject ();
					$current_subject->load ( $row ['id'] );
					echo $current_subject->display_subject ();
				}
			}
			break;
		
		case "new_subject" :
			$new_subject = new Subject ();
			$new_subject->subject_type_id = $_POST ['subject_type_id'];
			$new_subject->name = $_POST ['name'];
			$new_subject->save ();
			echo "Saved.";
			break;
		
		case "edit_subject" :
			$current_subject = new Subject ();
			$current_subject->load ( $_POST ['subject_id'] );
			$current_subject->name = $_POST ['name'];
			$current_subject->subject_type_id = $_POST ['subject_type_id'];
			$current_subject->update ();
			echo "saved.";
			break;
		
		case "delete_subject" :
			$current_subject = new Subject ();
			$current_subject->load ( $_POST ['subject_id'] );
			$current_subject->delete ();
			echo "deleted";
			break;
		
		default :
			new LogEntry ( __FILE__ . " was hit with an invalid action, from IP: " . $_SERVER ['REMOTE_ADDR'] );
			echo ('There was an error.  It has been logged.');
	}
} else {
	new LogEntry ( __FILE__ . " was hit with no action, from IP: " . $_SERVER ['REMOTE_ADDR'] );
	echo ('There was an error.  It has been logged.');
}

?>