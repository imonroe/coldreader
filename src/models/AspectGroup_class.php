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
class AspectGroup {
	public $id;
	public $group_name;
	public $create_date;
	public $update_date;
	public $aspects;
	public function __construct() {
		$this->aspects = array ();
	}
	public function __destruct() {
	}
	public function load($id = '') {
		$db = Database::get_instance ();
		if ($id == '') {
			$id = $this->id;
		}
		$query = $db->prepare ( "SELECT * FROM aspect_groups WHERE id=?" );
		$data = array (
				$id 
		);
		if ($query->execute ( $data )) {
			extract ( $query->fetch () );
			$this->id = $id;
			$this->group_name = $group_name;
			$this->create_date = $create_date;
			$this->update_date = $update_date;
			
			$query2 = $db->prepare ( "SELECT * FROM aspect_types_aspect_groups WHERE aspect_group =?" );
			$data2 = array (
					$this->id 
			);
			if ($query2->execute ( $data2 )) {
				while ( $row = $query2->fetch () ) {
					$at = new AspectType ();
					$at->load ( $row ['aspect_type'] );
					$this->aspects [] = $at;
				}
			}
			return true;
		} else {
			return false;
		}
	}
	public function save() {
		$db = Database::get_instance ();
		$time_saved = sql_datetime ();
		$query = $db->prepare ( "INSERT INTO aspect_groups (group_name, create_date, update_date) VALUES (?, ?, ?)" );
		$data = array (
				$this->group_name,
				$time_saved,
				$time_saved 
		);
		if ($query->execute ( $data )) {
			$this->id = $db->lastInsertId ();
			return true;
		} else {
			return false;
		}
	}
	public function update() {
		$db = Database::get_instance ();
		$time_updated = sql_datetime ();
		$query = $db->prepare ( "UPDATE aspect_groups SET group_name=?, update_date=? WHERE id=?" );
		$data = array (
				$this->group_name,
				$time_updated,
				$this->id 
		);
		if ($query->execute ( $data )) {
			return true;
		} else {
			return false;
		}
	}
	public function delete() {
		if ($db->exec ( "DELETE from aspect_groups WHERE id='" . $this->id . "'" )) {
			return true;
		} else {
			return false;
		}
	}
	public function get_html_dropdown_list_DEPRECATED() {
		$output = '';
		$db = Database::get_instance ();
		$query = $db->prepare ( "SELECT * FROM aspect_types WHERE aspect_group=?" );
		$data = array (
				$this->id 
		);
		if ($query->execute ( $data )) {
			while ( $row = $query->fetch () ) {
				$output .= '<option value="' . $row ["id"] . '">' . $row ["aspect_name"] . '</option>';
			}
			return $output;
		} else {
			return false;
		}
	}
	public function get_html_dropdown_list() {
		$output = '';
		foreach ( $this->aspects as $asp ) {
			$output .= '<option value="' . $asp->id . '">' . $asp->aspect_name . '</option>';
		}
		return $output;
	}
	public function get_group_members() {
		// returns an array of the form array['aspect_type_id'] = aspect_name
		$output = array ();
		foreach ( $this->aspects as $asp ) {
			$output [$asp->id] = $asp->aspect_name;
		}
		return $output;
	}
	public function get_nongroup_members() {
		// returns an array of aspects which are NOT currently assigned to the group
		// in the form of array['aspect_type_id'] = aspect_name
		$output = array ();
		$db = Database::get_instance ();
		$query_statement = "SELECT id, aspect_name FROM aspect_types";
		if (count ( $this->aspects ) > 0) {
			$already_members = '(';
			foreach ( $this->aspects as $asp ) {
				$already_members .= "'" . $asp->id . "', ";
			}
			if (strlen ( $already_members ) > 4) {
				$already_members = substr ( $already_members, 0, - 2 );
			}
			$already_members .= ')';
			$query_statement .= " WHERE id NOT IN " . $already_members;
		}
		$query = $db->prepare ( $query_statement );
		if ($query->execute ()) {
			while ( $row = $query->fetch () ) {
				$output [$row ['id']] = $row ['aspect_name'];
			}
		}
		return $output;
	}
	public function quick_add_aspect_type($aspect_type_id) {
		$db = Database::get_instance ();
		$query = $db->prepare ( "INSERT INTO aspect_types_aspect_groups (aspect_type, aspect_group) VALUES (?, ?)" );
		$data = array (
				$aspect_type_id,
				$this->id 
		);
		if ($query->execute ( $data )) {
			$this->load ();
			return true;
		} else {
			return false;
		}
	}
	public function quick_remove_aspect_type($aspect_type_id) {
		$db = Database::get_instance ();
		$query = $db->prepare ( "DELETE FROM aspect_types_aspect_groups WHERE aspect_type =? AND aspect_group =?" );
		$data = array (
				$aspect_type_id,
				$this->id 
		);
		if ($query->execute ( $data )) {
			$this->load ();
			return true;
		} else {
			return false;
		}
	}
} // end AspectGroup class.

?>