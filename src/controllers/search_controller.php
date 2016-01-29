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
		case "get_results" :
			// First, check if the subject exists.
			$output = '<h2>Subject Results:</h2>';
			$q = $_POST ['query'];
			$db = Database::get_instance ();
			$query = $db->prepare ( "SELECT id, subject_name, date_updated FROM subjects WHERE subject_name LIKE ? ORDER BY subject_name" );
			$data = array (
					'%' . $q . '%' 
			);
			if ($query->execute ( $data )) {
				while ( $row = $query->fetch () ) {
					$output .= '<p><a href="index.php?p=subject_view&id=' . $row ['id'] . '">' . $row ['subject_name'] . '</a><br /><span class="small">Updated: ' . $row ['date_updated'] . '</span></p>';
				}
			}
			
			// Next, see if the query appears in any aspects, and return the Subject if that's the case.
			
			$aspect_q = $db->prepare ( "SELECT * FROM aspects INNER JOIN aspect_types ON aspects.aspect_type=aspect_types.id INNER JOIN subjects_aspects ON aspects.id=subjects_aspects.aspect_id WHERE aspect_data LIKE ?" );
			if ($aspect_q->execute ( $data )) {
				$output .= "<h3>Aspect Results:</h3>";
				while ( $row = $aspect_q->fetch () ) {
					$ts = new Subject ();
					$ts->load ( $row ['subject_id'] );
					$output .= '<p>';
					$output .= 'Found in an aspect of ' . $ts->print_name ();
					$output .= '</p>';
				}
			}
			
			// finally, let's query the web.
			
			$ddg = new DuckAgent ();
			$ddg->query ( $q );
			if (! empty ( $ddg->result )) {
				$output .= '<h3>From the web:</h3>';
				
				$websearch = json_decode ( $ddg->result, true );
				if (isset ( $websearch ['AbstractText'] )) {
					$output .= '<p>Abstract: ' . $websearch ['AbstractText'] . '<br /> <span class="small">Source: ' . $websearch ['AbstractSource'] . '</span></p>';
				}
				if (! empty ( $websearch ['Definition'] )) {
					$output .= '<p>Definition: ' . $websearch ['Definition'] . '</p>';
				}
				if (! empty ( $websearch ['Results'] )) {
					$output .= '<h4>Search Results:</h4>';
					$output .= '<ul>';
					foreach ( $websearch ['Results'] as $r ) {
						$output .= '<li>' . stripslashes ( $r ['Result'] ) . '</li>';
					}
					$output .= '</ul>';
				}
				if (! empty ( $websearch ['RelatedTopics'] )) {
					$output .= '<h4>Related Topics:</h4>';
					$output .= '<ul>';
					foreach ( $websearch ['RelatedTopics'] as $r ) {
						if (! empty ( $r ['Result'] )) {
							$output .= '<li>' . stripslashes ( $r ['Result'] ) . '</li>';
						}
					}
					$output .= '</ul>';
				}
			}
			
			echo $output;
			break;
		
		case "autocomplete" :
			$a_json = array ();
			$a_json_row = array ();
			$db = Database::get_instance ();
			$query = $db->prepare ( "SELECT id, subject_name FROM subjects WHERE subject_name LIKE ? ORDER BY subject_name" );
			$data = array (
					'%' . $term . '%' 
			);
			if ($query->execute ( $data )) {
				while ( $row = $query->fetch () ) {
					$a_json_row ["id"] = $row ['id'];
					$a_json_row ["value"] = $row ['subject_name'];
					$a_json_row ["label"] = $row ['subject_name'];
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