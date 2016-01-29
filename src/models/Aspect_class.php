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
class Aspect {
	public $id;
	public $aspect_type;
	public $aspect_data;
	public $aspect_binary;
	public $predicted_accuracy;
	public $create_date;
	public $update_date;
	public $aspect_notes;
	public $aspect_source;
	public $markdown;
	public $is_hidden;
	public $hash;
	public $last_parsed;
	public function to_array() {
		$output = array (
				'id' => $this->id,
				'aspect_type' => $this->aspect_type,
				'aspect_data' => $this->aspect_data,
				'aspect_binary' => $this->aspect_binary,
				'predicted_accuratcy' => $this->predicted_accuracy,
				'create_date' => $this->create_date,
				'update_date' => $this->update_date,
				'aspect_source' => $this->aspect_source,
				'aspect_notes' => $this->aspect_notes,
				'markdown' => $this->markdown,
				'is_hidden' => $this->is_hidden,
				'hash' => $this->get_hash (),
				'last_parsed' => $this->last_parsed 
		);
		return $output;
	}
	public function __construct($id = '') {
		$this->markdown = 1;
		if ($id != '') {
			$this->load ( ( int ) $id );
		}
	}
	public function __destruct() {
	}
	public function load($id = '') {
		$db = Database::get_instance ();
		if ($id == '') {
			$id = $this->id;
		}
		$query = $db->prepare ( "SELECT * FROM aspects WHERE id=?" );
		$data = array (
				$id 
		);
		if ($query->execute ( $data )) {
			extract ( $query->fetch () );
			$this->id = $id;
			$this->aspect_type = $aspect_type;
			$this->aspect_data = $aspect_data;
			$this->aspect_binary = $aspect_binary;
			$this->predicted_accuracy = $predicted_accuracy;
			$this->create_date = $create_date;
			$this->update_date = $update_date;
			$this->aspect_notes = $aspect_notes;
			$this->aspect_source = $aspect_source;
			$this->is_hidden = $hidden;
			$this->hash = $hash;
			$this->last_parsed = $last_parsed;
			
			// see if the aspect type is viewable and update here.
			$viewable_type = new AspectType ();
			$viewable_type->load ( $this->aspect_type );
			if ($viewable_type->is_viewable) {
				$this->is_hidden = false;
			} else {
				$this->is_hidden = true;
			}
			
			if (( int ) $viewable_type->markdown == 1) {
				$this->markdown = true;
			} else {
				$this->markdown = false;
			}
			
			return true;
		} else {
			return false;
		}
	}
	public function save() {
		$this->get_hash ();
		$db = Database::get_instance ();
		$time_saved = sql_datetime ();
		$query = $db->prepare ( "INSERT INTO aspects (aspect_type, aspect_data, aspect_binary, predicted_accuracy, create_date, update_date, aspect_notes, aspect_source, last_parsed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)" );
		$data = array (
				$this->aspect_type,
				$this->aspect_data,
				$this->aspect_binary,
				$this->predicted_accuracy,
				$time_saved,
				$time_saved,
				$this->aspect_notes,
				$this->aspect_source,
				$this->last_parsed 
		);
		if ($query->execute ( $data )) {
			$this->id = $db->lastInsertId ();
			return true;
		} else {
			return false;
		}
	}
	public function update() {
		$this->get_hash ();
		$db = Database::get_instance ();
		$time_updated = sql_datetime ();
		$query = $db->prepare ( "UPDATE aspects SET aspect_type=?, aspect_data=?, aspect_binary=?, predicted_accuracy=?, update_date=?, aspect_notes=?, aspect_source=?, hidden=?, hash=?, last_parsed=? WHERE id=?" );
		$data = array (
				$this->aspect_type,
				$this->aspect_data,
				$this->aspect_binary,
				$this->predicted_accuracy,
				$time_updated,
				$this->aspect_notes,
				$this->aspect_source,
				$this->is_hidden,
				$this->hash,
				$this->last_parsed,
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
		if ($db->exec ( "DELETE from aspects WHERE id='" . $this->id . "'" )) {
			return true;
		} else {
			return false;
		}
	}
	public function get_hash() {
		// $this->hash = md5($this->aspect_data);
	}
	public function return_subject_id() {
		$output = false;
		$db = Database::get_instance ();
		$query = $db->prepare ( "SELECT subject_id FROM subjects_aspects WHERE aspect_id=?" );
		$data = array (
				$this->id 
		);
		if ($query->execute ( $data )) {
			foreach ( $query->fetchAll () as $row ) {
				$output = $row ['subject_id'];
			}
			return $output;
		} else {
			return false;
		}
	}
	public function return_subject_name() {
		$sub = new Subject ();
		$sub->load ( $this->return_subject_id () );
		return $sub->name;
	}
	public function return_aspect_type_name() {
		$output = false;
		$db = Database::get_instance ();
		$query = $db->prepare ( "SELECT aspect_name FROM aspect_types WHERE id=?" );
		$data = array (
				$this->aspect_type 
		);
		if ($query->execute ( $data )) {
			foreach ( $query->fetchAll () as $row ) {
				$output = $row ['aspect_name'];
			}
			return $output;
		} else {
			return false;
		}
	}
	public function return_aspect_type_id() {
		return $this->aspect_type;
	}
	public function return_aspect_group_name() {
		$output = false;
		$db = Database::get_instance ();
		$query = $db->prepare ( "SELECT group_name FROM aspect_groups WHERE id=?" );
		$data = array (
				$this->return_aspect_type_id () 
		);
		if ($query->execute ( $data )) {
			foreach ( $query->fetch () as $row ) {
				$output = $row ['group_name'];
			}
			return $output;
		} else {
			return false;
		}
	}
	public function return_aspect_group_id() {
		/*
		 * $output = false;
		 * $db=Database::get_instance();
		 * $query = $db->prepare("SELECT aspect_group FROM aspect_types WHERE id=?");
		 * $data = array($this->id);
		 * if ($query->execute($data)) {
		 * foreach($query->fetchAll() as $row){
		 * $output = $row['aspect_group'];
		 * }
		 * return $output;
		 * } else { return false;}
		 */
		return false;
	}
	public function display_aspect_panel() {
		$output = '<div class="panel panel-default">';
		$output .= '<div class="panel-heading">';
		$output .= '<h3 class="panel-title">' . $this->return_aspect_type_name () . '</h3>';
		$output .= '</div>';
		$output .= '<div class="panel-body">';
		$output .= '<div id="aspect_data_' . $this->id . '">' . $this->aspect_data . '</div>';
		if ($this->aspect_source != '') {
			$output .= '<p class="small">Source: ' . $this->aspect_source . '</p>';
		}
		if ($this->predicted_accuracy != '') {
			$output .= '<p class="small">Predicted accuracy: ' . $this->predicted_accuracy . '</p>';
		}
		if ($this->aspect_notes != '') {
			$output .= '<div id="notes_data' . $this->id . '"><p>Notes</p><hr />' . $this->predicted_accuracy . '</div>';
		}
		$output .= '</div>';
		$output .= '</div>';
		return $output;
	}
	public function display_aspect() {
		if (! $this->is_hidden) {
			if ($this->markdown) {
				$output_data = '';
				$parser = new Parsedown ();
				$output_data = $parser->text ( $this->aspect_data );
			} else {
				$output_data = $this->aspect_data;
			}
			$output = '<div id="aspect_' . $this->id . '">';
			$output .= '<p><strong>' . $this->return_aspect_type_name () . ': </strong>' . $this->aspect_data;
			$output .= ' <span class="small">(<a href="index.php?p=form_edit_aspect&id=' . $this->id . '">Edit</a>)</span>';
			$output .= '</p>';
			$output .= '</div>';
			return $output;
		} else {
			return;
		}
	}
	public function parse() {
		$this->last_parsed = sql_datetime ();
		$this->update ();
	}
} // end Aspect class.

?>