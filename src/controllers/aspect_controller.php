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
$app = App::get_instance ();
$db = Database::get_instance ();
$action = NULL;
if (isset ( $_POST ['action'] )) {
	$action = trim ( $_POST ['action'] );
}

if (isset ( $action )) {
	switch ($action) {
		case "add_aspect_to_subject" :
			// do something.
			$output = '';
			// do we have an upload to process?
			
			$handle = new upload ( $_FILES ['aspect_binary'] );
			if ($handle->uploaded) {
				$output .= 'entered uploaded routine' . PHP_EOL;
				$output .= 'filename: ' . $handle->file_src_name . PHP_EOL;
				$output .= 'path: ' . $app ['uploads_path'] . PHP_EOL;
				$newfilename = $handle->file_src_name_body . '_upload' . strtotime ( "now" );
				$final_filename = $newfilename . '.' . $handle->file_src_name_ext;
				$output .= 'final filename: ' . $final_filename . PHP_EOL;
				$handle->file_new_name_body = $newfilename;
				
				$handle->process ( $app ['uploads_path'] );
				if ($handle->processed) {
					$file_location = $final_filename;
					$handle->clean ();
				} else {
					$output .= 'error : ' . $handle->error . PHP_EOL;
				}
			}
			$current_subject = new Subject ();
			$current_subject->load ( ( int ) $_POST ['subject_id'] );
			$new_aspect = new Aspect ();
			$new_aspect->aspect_type = ( int ) $_POST ['aspect_type'];
			if (isset ( $file_location )) {
				$new_aspect->aspect_data = $file_location;
			} else {
				$new_aspect->aspect_data = $_POST ['aspect_data'];
			}
			$new_aspect->aspect_binary = NULL;
			$new_aspect->predicted_accuracty = NULL;
			$new_aspect->aspect_notes = $_POST ['aspect_notes'];
			$new_aspect->aspect_source = $_POST ['aspect_source'];
			$new_aspect->save ();
			$current_subject->add_aspect ( $new_aspect );
			$output .= 'Added ' . $new_aspect->return_aspect_type_name () . ' to ' . $current_subject->name . '.';
			echo ($output);
			break;
		
		case "edit_aspect" :
			// do something.
			$new_aspect = new Aspect ();
			$new_aspect->load ( ( int ) $_POST ['aspect_id'] );
			$new_aspect->aspect_type = ( int ) $_POST ['aspect_type'];
			$new_aspect->aspect_data = $_POST ['aspect_data'];
			$new_aspect->aspect_binary = NULL;
			$new_aspect->predicted_accuracty = NULL;
			$new_aspect->aspect_notes = $_POST ['aspect_notes'];
			$new_aspect->aspect_source = $_POST ['aspect_source'];
			$new_aspect->update ();
			$output = 'Updated ' . $new_aspect->return_aspect_type_name () . '.';
			echo ($output);
			break;
		
		case "delete_aspect" :
			$new_aspect = new Aspect ();
			$new_aspect->load ( $_POST ['aspect_id'] );
			$new_aspect->delete ();
			echo "Deleted aspect.";
			break;
		
		case "new_aspect_type" :
			$new_aspect_type = new AspectType ();
			$new_aspect_type->aspect_groups [] = $_POST ['aspect_group'];
			$new_aspect_type->aspect_name = $_POST ['aspect_name'];
			$new_aspect_type->aspect_description = $_POST ['aspect_description'];
			$new_aspect_type->markdown = $_POST ['markdown'];
			$new_aspect_type->is_viewable = $_POST ['viewable'];
			$new_aspect_type->save ();
			echo "Added new aspect type.";
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