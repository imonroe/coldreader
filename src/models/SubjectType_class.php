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
class SubjectType {
	public $id;
	public $type_name;
	public $type_description;
	public $aspect_group;
	public $create_date;
	public $update_date;
	public $parent_id;
	public $children;
	public function __construct() {
		$this->children = array ();
	}
	public function __destruct() {
	}
	public function load($id = '') {
		$db = Database::get_instance ();
		if ($id == '') {
			$id = $this->id;
		}
		$query = $db->prepare ( "SELECT * FROM subject_types WHERE id=?" );
		$data = array (
				$id 
		);
		if ($query->execute ( $data )) {
			extract ( $query->fetch () );
			$this->id = $id;
			$this->type_name = $type_name;
			$this->type_description = $type_description;
			$this->aspect_group = $aspect_group;
			$this->create_date = $create_date;
			$this->update_date = $update_date;
			$this->parent_id = $parent_id;
			return true;
		} else {
			return false;
		}
	}
	public function save() {
		$db = Database::get_instance ();
		$time_saved = sql_datetime ();
		$query = $db->prepare ( "INSERT INTO subject_types (type_name, type_description, aspect_group, create_date, update_date, parent_id) VALUES (?, ?, ?, ?, ?, ?)" );
		$data = array (
				$this->type_name,
				$this->type_description,
				$this->aspect_group,
				$time_saved,
				$time_saved,
				$this->parent_id 
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
		$query = $db->prepare ( "UPDATE subject_types SET type_name=?, type_description=?, aspect_group=?, update_date=?, parent_id=? WHERE id=?" );
		$data = array (
				$this->type_name,
				$this->type_description,
				$this->aspect_group,
				$time_updated,
				$this->parent_id,
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
		if ($db->exec ( "DELETE from subject_types WHERE id='" . $this->id . "'" )) {
			return true;
		} else {
			return false;
		}
	}
	public function load_children() {
		$db = Database::get_instance ();
		$sql = "SELECT id FROM subject_types WHERE parent_id=?";
		$data = array (
				$this->id 
		);
		$query = $db->prepare ( $sql );
		if ($query->execute ( $data )) {
			while ( $row = $query->fetch () ) {
				$new_subject = new SubjectType ();
				$new_subject->load ( $row ['id'] );
				$this->children [] = $new_subject;
				$new_subject->load_children ();
				foreach ( $new_subject->children as $child ) {
					$this->children [] = $child;
				}
			}
		}
	}
	public function get_link() {
		return '<a href="index.php?p=list_subjects_by_type&id=' . $this->id . '">' . $this->type_name . '</a>';
	}
	public function get_all_children() {
		$this->load_children ();
		if (empty ( $this->children )) {
			return;
		} else {
			$output = '<ul>';
			foreach ( $this->children as $child ) {
				$output .= '<li>' . $child->get_link () . '</li>';
				$output .= $child->get_all_children ();
			}
			$output .= '</ul>';
			return $output;
		}
	}
	public function get_all_children_dropdown() {
		$this->load_children ();
		if (empty ( $this->children )) {
			return;
		} else {
			$output = '';
			foreach ( $this->children as $child ) {
				$output .= '<option value="' . $child->id . '">' . $child->type_name . '</option>';
				$output .= $child->get_all_children_dropdown ();
			}
			return $output;
		}
	}
	public function get_parent_name() {
		if (empty ( $this->parent_id )) {
			return;
		} else {
			$parent = new SubjectType ();
			$parent->load ( $this->parent_id );
			$output = $parent->get_link ();
			if (! empty ( $parent->parent_id )) {
				$output .= ' -> ' . $parent->get_parent_name ();
			}
			return $output;
		}
	}
	public function what_am_i() {
		$output = $this->get_link ();
		if (! empty ( $this->parent_id )) {
			$output .= ' -> ' . $this->get_parent_name ();
		}
		return $output;
	}
} // end SubjectType class.

?>