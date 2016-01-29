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
class Relationship {
	public $id;
	public $date_created;
	public $date_updated;
	public $subject_id_1;
	public $subject_1_name;
	public $subject_id_2;
	public $subject_2_name;
	public $description;
	public function to_array() {
		$output = array (
				'id' => $this->id,
				'date_created' => $this->date_created,
				'date_updated' => $this->date_updated,
				'subject_id_1' => $this->subject_id_1,
				'subject_1_name' => $this->subject_1_name,
				'subject_id_2' => $this->subject_id_2,
				'subject_2_name' => $this->subject_2_name,
				'description' => $this->description 
		);
		return $output;
	}
	public function _construct() {
	}
	public function _destruct() {
	}
	public function load($id = '') {
		$db = Database::get_instance ();
		if ($id == '') {
			$id = $this->id;
		}
		$query = $db->prepare ( "SELECT * FROM relationships WHERE id=?" );
		$data = array (
				$id 
		);
		if ($query->execute ( $data )) {
			extract ( $query->fetch () );
			$this->id = $id;
			$this->date_created = $date_created;
			$this->date_updated = $date_updated;
			$this->subject_id_1 = $subject_id_1;
			$this->subject_id_2 = $subject_id_2;
			$this->description = $description;
			$this->get_names ();
			return true;
		} else {
			return false;
		}
	}
	public function get_names() {
		$temp_subject = new Subject ();
		$temp_subject->load ( $this->subject_id_1 );
		$this->subject_1_name = $temp_subject->print_name ();
		$temp_subject->load ( $this->subject_id_2 );
		$this->subject_2_name = $temp_subject->print_name ();
	}
	public function save() {
		$db = Database::get_instance ();
		$time_saved = sql_datetime ();
		$query = $db->prepare ( "INSERT INTO relationships (date_created, date_updated, subject_id_1, subject_id_2, description) values (?, ?, ?, ?, ?)" );
		$data = array (
				$time_saved,
				$time_saved,
				$this->subject_id_1,
				$this->subject_id_2,
				$this->description 
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
		$query = $db->prepare ( "UPDATE relationships SET date_update=?, subject_id_1=?, subject_id_2=?, description=? WHERE id=?" );
		$data = array (
				$time_updated,
				$this->subject_id_1,
				$this->subject_id_2,
				$this->description,
				$this->id 
		);
		if ($query->execute ( $data )) {
			return true;
		} else {
			return false;
		}
	}
	public function delete() {
		$db = Database::get_instance ();
		if ($db->exec ( "DELETE from relationships WHERE id='" . $this->id . "'" )) {
			return true;
		} else {
			return false;
		}
	}
} // end Relationship class.

?>