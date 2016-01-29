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
class AspectType {
	public $id;
	public $aspect_groups; // array of aspect_group ids
	public $aspect_name;
	public $aspect_description;
	public $flavor;
	public $create_date;
	public $update_date;
	public $is_viewable;
	public $markdown;
	public function __construct() {
	}
	public function __destruct() {
	}
	public function load($id = '') {
		$db = Database::get_instance ();
		if ($id == '') {
			$id = $this->id;
		}
		$query = $db->prepare ( "SELECT * FROM aspect_types WHERE id=?" );
		$data = array (
				$id 
		);
		if ($query->execute ( $data )) {
			extract ( $query->fetch () );
			$this->id = $id;
			// $this->aspect_group = $aspect_group;
			$this->aspect_name = $aspect_name;
			$this->aspect_description = $aspect_description;
			$this->create_date = $create_date;
			$this->update_date = $update_date;
			$this->flavor = $flavor;
			$this->is_viewable = ( bool ) $is_viewable;
			$this->markdown = $markdown;
			$this->load_aspect_groups ();
			return true;
		} else {
			return false;
		}
	}
	public function load_aspect_groups() {
		$db = Database::get_instance ();
		$this->aspect_groups = array ();
		$agquery = $db->prepare ( "SELECT * from aspect_types_aspect_groups WHERE aspect_type =?" );
		$agdata = array (
				$this->id 
		);
		if ($agquery->execute ( $agdata )) {
			while ( $agrow = $agquery->fetch () ) {
				$this->aspect_groups [] = $agrow ['aspect_group'];
			}
		}
	}
	public function save_aspect_groups() {
		$db = Database::get_instance ();
		foreach ( $this->aspect_groups as $ag ) {
			// check if the aspect_group record is already in the table.
			$already_exists = false;
			$query = $db->prepare ( "SELECT id FROM aspect_types_aspect_groups WHERE aspect_type =? AND aspect_group=?" );
			$data = array (
					$this->id,
					$ag 
			);
			if ($query->execute ( $data )) {
				while ( $row = $query->fetch () ) {
					$already_exists = true;
				}
			}
			// now we know if the record exists. If it does not, go ahead and add it.
			if (! $already_exists) {
				// add the record.
				$query = $db->prepare ( "INSERT INTO aspect_types_aspect_groups (aspect_type, aspect_group) VALUES (?, ?)" );
				$data = array (
						$this->id,
						$ag 
				);
				$query->execute ( $data );
			}
		}
	}
	public function clean_up_aspect_groups() {
		$db = Database::get_instance ();
	}
	public function save() {
		$db = Database::get_instance ();
		$time_saved = sql_datetime ();
		$query = $db->prepare ( "INSERT INTO aspect_types (aspect_name, aspect_description, create_date, update_date, flavor, is_viewable, markdown) VALUES (?, ?, ?, ?, ?, ?, ?)" );
		$data = array (
				$this->aspect_name,
				$this->aspect_description,
				$time_saved,
				$time_saved,
				$this->flavor,
				$this->is_viewable,
				$this->markdown 
		);
		if ($query->execute ( $data )) {
			$this->id = $db->lastInsertId ();
			$this->save_aspect_groups ();
			$this->create_custom_aspect_class ();
			return true;
		} else {
			return false;
		}
	}
	public function update() {
		$db = Database::get_instance ();
		$time_updated = sql_datetime ();
		$query = $db->prepare ( "UPDATE aspect_types SET aspect_group=?, aspect_name=?, aspect_description=?, update_date=?, flavor=?, is_viewable=?, markdown=? WHERE id=?" );
		$data = array (
				$this->aspect_group,
				$this->aspect_name,
				$this->aspect_description,
				$time_updated,
				$this->flavor,
				$this->is_viewable,
				$this->markdown,
				$this->id 
		);
		if ($query->execute ( $data )) {
			return true;
		} else {
			return false;
		}
	}
	public function delete() {
		if ($db->exec ( "DELETE from aspect_types WHERE id='" . $this->id . "'" ) && $db->exec ( "DELETE from aspect_types_aspect_groups WHERE aspect_type ='" . $this->id . "'" )) {
			return true;
		} else {
			return false;
		}
	}
	public function create_custom_aspect_class() {
		echo "entered Custom Class" . PHP_EOL;
		$app = App::get_instance ();
		$new_classname = code_safe_name ( $this->aspect_name );
		$new_classname = $new_classname . 'Aspect';
		$output = "// default custom class created automatically." . PHP_EOL . PHP_EOL;
		$output .= 'class ' . $new_classname . ' extends Aspect{' . PHP_EOL;
		$output .= "\t" . 'public function display_aspect(){' . PHP_EOL;
		$output .= "\t" . "\t" . '$output = parent::display_aspect();' . PHP_EOL;
		$output .= "\t" . "\t" . 'return $output;' . PHP_EOL;
		$output .= '}' . PHP_EOL;
		$output .= "\t" . 'public function parse(){}' . PHP_EOL;
		$output .= '}' . PHP_EOL;
		$output .= PHP_EOL . PHP_EOL;
		$output .= '// end file';
		
		$path_to_custom_aspects = $app ['model_path'] . '/CustomAspects_class.php';
		$file_contents = file_get_contents ( $path_to_custom_aspects );
		$file_contents = str_replace ( '// end file', $output, $file_contents );
		if (file_put_contents ( $path_to_custom_aspects, $file_contents )) {
			return "Put properly.";
		} else {
			return "did not put properly.";
		}
	}
} // end AspectType class.

?>