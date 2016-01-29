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
/* -- Subjects -- */
class Subject {
	public $id;
	public $subject_type_id;
	public $name;
	public $date_created;
	public $date_updated;
	public $aspects;
	public $relationships;
	public function to_array() {
		$this->load_aspects ();
		$this->load_relationships ();
		$output = array (
				'id' => $this->id,
				'subject_type_id' => $this->subject_type_id,
				'name' => $this->name,
				'date_created' => $this->date_created,
				'date_updated' => $this->date_updated,
				'aspects' => array (),
				'relationships' => array () 
		);
		
		foreach ( $this->aspects as $a ) {
			$output ['aspects'] [] = $a->to_array ();
		}
		
		foreach ( $this->relationships as $r ) {
			$output ['relationships'] [] = $r->to_array ();
		}
		return $output;
	}
	public function __construct($id = '') {
		if ($id != '') {
			$this->load ( $id );
		}
	}
	public function __destruct() {
	}
	public function load($id = '') {
		$db = Database::get_instance ();
		if ($id == '') {
			$id = $this->id;
		}
		$query = $db->prepare ( "SELECT * FROM subjects WHERE id=?" );
		$data = array (
				$id 
		);
		if ($query->execute ( $data )) {
			extract ( $query->fetch () );
			$this->id = $id;
			$this->subject_type_id = $subject_type;
			$this->name = $subject_name;
			$this->date_created = $date_created;
			$this->date_updated = $date_updated;
			// $this->load_aspects();
			// $this->load_relationships();
			return true;
		} else {
			return false;
		}
	}
	public function load_from_name($name = '') {
		$db = Database::get_instance ();
		if ($name != '') {
			$this->name = $name;
		}
		$query = $db->prepare ( 'SELECT id FROM subjects WHERE subject_name =?' );
		$data = array (
				$name 
		);
		if ($query->execute ( $data )) {
			extract ( $query->fetch () );
			$this->load ( $id );
		} else {
			return false;
		}
	}
	public function save() {
		$db = Database::get_instance ();
		$time_saved = sql_datetime ();
		$query = $db->prepare ( "INSERT INTO subjects (subject_name, subject_type, date_created, date_updated) values (?, ?, ?, ?)" );
		$data = array (
				$this->name,
				$this->subject_type_id,
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
		$query = $db->prepare ( "UPDATE subjects SET subject_name=?, subject_type=?, date_updated=? WHERE id=?" );
		$data = array (
				$this->name,
				$this->subject_type_id,
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
		$db = Database::get_instance ();
		// if we're deleting the subject, we should delete any aspects in the DB attached to that subject.
		foreach ( $this->aspects as $a ) {
			$a->delete ();
		}
		// same for relationships
		foreach ( $this->relationships as $r ) {
			$r->delete ();
		}
		// then delete the subject record.
		if ($db->exec ( "DELETE from subjects WHERE id='" . $this->id . "'" )) {
			return true;
		} else {
			return false;
		}
	}
	public function load_aspects() {
		$this->aspects = array ();
		$db = Database::get_instance ();
		$query = $db->prepare ( "SELECT subjects_aspects.aspect_id, aspects.aspect_type FROM subjects_aspects INNER JOIN aspects ON subjects_aspects.aspect_id=aspects.id WHERE subject_id=?" );
		$data = array (
				$this->id 
		);
		if ($query->execute ( $data )) {
			while ( $row = $query->fetch () ) {
				// $new_aspect = new Aspect();
				$new_aspect = AspectFactory::create ( $row ['aspect_type'] );
				$new_aspect->load ( $row ['aspect_id'] );
				$this->aspects [] = $new_aspect;
			}
			return true;
		} else {
			return false;
		}
	}
	public function add_aspect($aspect_object) {
		$db = Database::get_instance ();
		$query = $db->prepare ( "INSERT INTO subjects_aspects (subject_id, aspect_id) VALUES (?, ?)" );
		$data = array (
				$this->id,
				$aspect_object->id 
		);
		if ($query->execute ( $data ) && $this->load_aspects ()) {
			$this->update ();
			return true;
		} else {
			return false;
		}
	}
	public function remove_aspect($aspect_id) {
		$db = Database::get_instance ();
		if (($db->exec ( "DELETE from subjects_aspects WHERE subject_id='" . $this->id . "' AND aspect_id='" . $aspect_id . "'" )) && $this->load_aspects ()) {
			return true;
		} else {
			return false;
		}
	}
	public function display_subject() {
		$this->load_aspects ();
		$output = '<h1>' . $this->name . ' <span class="small">(<a href="index.php?p=form_edit_subject&id=' . $this->id . '">Edit</a>)</span></h1>';
		$output .= '<p class="small">Subject type: ' . $this->what_am_i () . '</p>';
		$output .= '<p class="small">Created: ' . standard_date_format ( strtotime ( $this->date_created ) ) . '</p>';
		$output .= '<p class="small">Updated: ' . standard_date_format ( strtotime ( $this->date_updated ) ) . '</p>';
		$output .= '<a href="index.php?p=form_add_aspect&id=' . $this->id . '" class="btn btn-primary">Add a new aspect</a>';
		$output .= '<hr />';
		foreach ( $this->aspects as $aspect ) {
			$output .= $aspect->display_aspect ();
		}
		$output .= $this->print_relationships ();
		$output .= '<a name="relationship_anchor" id="relationship_anchor"></a>';
		return $output;
	}
	public function get_aspect_group_id() {
		$output = '';
		$db = Database::get_instance ();
		$query = $db->prepare ( "SELECT aspect_group FROM subject_types WHERE id=? " );
		$data = array (
				$this->subject_type_id 
		);
		if ($query->execute ( $data )) {
			while ( $row = $query->fetch () ) {
				$output = ( int ) $row ['aspect_group'];
			}
			return $output;
		} else {
			return false;
		}
	}
	public function what_am_i() {
		$my_subject_type = new SubjectType ();
		$my_subject_type->load ( $this->subject_type_id );
		return $my_subject_type->what_am_i ();
	}
	public function load_relationships() {
		$db = Database::get_instance ();
		$this->relationships = array ();
		$query = $db->prepare ( "SELECT id FROM relationships WHERE (subject_id_1=? OR subject_id_2=?)" );
		$data = array (
				$this->id,
				$this->id 
		);
		if ($query->execute ( $data )) {
			while ( $row = $query->fetch () ) {
				$new_relationship = new Relationship ();
				$new_relationship->load ( $row ['id'] );
				$this->relationships [] = $new_relationship;
			}
			return true;
		} else {
			return false;
		}
	}
	public function print_name() {
		return '<a href="index.php?p=subject_view&id=' . $this->id . '">' . $this->name . '</a>';
	}
	public function print_relationships() {
		if (empty ( $this->relationships )) {
			$this->load_relationships ();
		}
		$output = '<h2>Relationships:</h2>';
		$output .= '<ul>';
		foreach ( $this->relationships as $rel ) {
			$rel->get_names ();
			$output .= '<li>' . $rel->subject_1_name . ' -> ' . $rel->subject_2_name . ' (' . $rel->description . ') </li>';
		}
		$output .= '</ul>';
		return $output;
	}
	public function has_aspect($aspect_type_id) {
		// Returns an aspect_id if it's present, otherwise, returns FALSE.
		if (empty ( $this->aspects )) {
			$this->load_aspects ();
		}
		$is_it_there = false;
		foreach ( $this->aspects as $chk ) {
			if ($chk->aspect_type == $aspect_type_id) {
				$is_it_there = $chk->id;
			}
		}
		return $is_it_there;
	}
	public function has_aspect_named($aspect_name_string) {
		// lets you use strings instead of aspect type ids.
		$x = AspectFactory::create ( $aspect_name_string );
		$x_id = $x->aspect_type;
		$answer = $this->has_aspect ( $x_id );
		return $answer;
	}
	public function get_aspect_data($aspect_type_id) {
		// If the Aspect_type is present, return the data. Otherwise, return false.
		$output = false;
		if ($this->has_aspect ( $aspect_type_id )) {
			$a = new Aspect ();
			$a->load ( $this->has_aspect ( $aspect_type_id ) );
			$output = $a->aspect_data;
		}
		return $output;
	}
	public function quick_add($aspect_type_name, $aspect_data) {
		// $current_aspect_group = new AspectGroup;
		// $current_aspect_group->load($this->get_aspect_group_id());
		// $aspect_type_array = $current_aspect_group->get_group_members();
		// $new_aspect_id = array_search($aspect_type_name, $aspect_type_array);
		// if ($new_aspect_id){
		$new_aspect = AspectFactory::create ( $aspect_type_name, $aspect_data );
		$new_aspect->save ();
		$this->add_aspect ( $new_aspect );
		// }
	}
} // end Subject class.

?>