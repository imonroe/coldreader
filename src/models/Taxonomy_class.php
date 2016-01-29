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
class Taxonomy {
	public $subjects;
	public $full_subject_tree;
	public $full_subjects_array;
	public $tree_array;
	public function _construct($subject_type_id = '') {
		$this->subjects = array ();
		$this->full_subjects_array = array ();
		$this->tree_array = array ();
		$this->load ( $subject_type_id );
	}
	public function _destruct() {
	}
	public function load($subject_type_id = '') {
		$db = Database::get_instance ();
		$sql = "SELECT * FROM subject_types";
		$data = array (
				$subject_type_id 
		);
		
		if ($subject_type_id != '') {
			$sql .= " WHERE id =?";
			$query = $db->prepare ( $sql );
			$query->execute ( $data );
		} else {
			$sql .= " WHERE parent_id IS NULL";
			$query = $db->prepare ( $sql );
			$query->execute ();
		}
		while ( $row = $query->fetch () ) {
			$new_subject = new SubjectType ();
			$new_subject->load ( $row ['id'] );
			$this->subjects [] = $new_subject;
		}
	}
	public function load_full_subjects_array() {
		$db = Database::get_instance ();
		$query = $db->prepare ( "SELECT id FROM subject_types" );
		if ($query->execute ()) {
			while ( $row = $query->fetch () ) {
				$new_subject = new SubjectType ();
				$new_subject->load ( $row ['id'] );
				$this->full_subjects_array [$new_subject->id] = $new_subject->parent_id;
			}
		}
	}
	public function parse_tree($tree, $root = null) {
		$output = array ();
		foreach ( $tree as $child => $parent ) {
			if ($parent == $root) {
				unset ( $tree [$child] );
				$output [] = array (
						'id' => $child,
						'children' => $this->parse_tree ( $tree, $child ) 
				);
			}
		}
		return $output;
	}
	function printTree($tree) {
		$output = '';
		if (! is_null ( $tree ) && count ( $tree ) > 0) {
			$output .= '<ul>';
			foreach ( $tree as $node ) {
				$tmp_subject_type = new SubjectType ();
				$tmp_subject_type->load ( $node ['id'] );
				$output .= '<li>';
				$output .= $tmp_subject_type->get_link ();
				if (! empty ( $node ['children'] )) {
					$output .= $this->printTree ( $node ['children'] );
				}
				$output .= '</li>';
			}
			$output .= '</ul>';
		}
		return $output;
	}
	public function print_tree_view() {
		$this->load_full_subjects_array ();
		$this->tree_array = $this->parse_tree ( $this->full_subjects_array );
		return $this->printTree ( $this->tree_array );
	}
	public function get_taxonomy() {
		return $this->print_tree_view ();
	}
	public function get_taxonomy_DEPRICATED() {
		if (empty ( $this->subjects )) {
			return;
		} else {
			$output = '<ul>';
			foreach ( $this->subjects as $subject_type ) {
				$output .= '<li>' . $subject_type->get_link () . '</li>';
				$output .= $subject_type->get_all_children ();
			}
			$output .= '</ul>';
			return $output;
		}
	}
	public function get_taxonomy_dropdown() {
		if (empty ( $this->subjects )) {
			return;
		} else {
			$output = '';
			foreach ( $this->subjects as $subject_type ) {
				$output .= '<option value="' . $subject_type->id . '">' . $subject_type->type_name . '</option>';
				$output .= $subject_type->get_all_children_dropdown ();
			}
			return $output;
		}
	}
} // end Taxonomy class.

?>