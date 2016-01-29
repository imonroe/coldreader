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
class HTMLDropDown {
	function _construct() {
	}
	function _destruct() {
	}
	public function aspect_groups() {
		$output = '';
		$db = Database::get_instance ();
		$query = $db->prepare ( "SELECT id, group_name FROM aspect_groups" );
		if ($query->execute ()) {
			foreach ( $query->fetchAll () as $row ) {
				$output .= '<option value="' . $row ['id'] . '">' . $row ['group_name'] . '</option>';
			}
			return $output;
		} else {
			return false;
		}
	}
	public function subject_types() {
		$output = '';
		$db = Database::get_instance ();
		$query = $db->prepare ( "SELECT id, type_name FROM subject_types" );
		if ($query->execute ()) {
			foreach ( $query->fetchAll () as $row ) {
				$output .= '<option value="' . $row ['id'] . '">' . $row ['type_name'] . '</option>';
			}
			return $output;
		} else {
			return false;
		}
	}
	public function aspect_flavors() {
		$output = '';
		$db = Database::get_instance ();
		$query = $db->prepare ( "SELECT DISTINCT flavor from aspect_types ORDER BY flavor ASC" );
		if ($query->execute ()) {
			foreach ( $query->fetchAll () as $row ) {
				$output .= '<option value="' . $row ['flavor'] . '">' . $row ['flavor'] . '</option>';
			}
			return $output;
		} else {
			return false;
		}
	}
} // end HTMLDropDown class

?>