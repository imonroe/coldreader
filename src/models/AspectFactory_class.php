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

/*
 * Ok, so this is a factory pattern, but we don't necessarily know what kind of sub-classes are going to exist in the future
 * so we're going to need to write code that invokes class names based on stuff that's passed in.
 *
 * From here: http://stackoverflow.com/questions/4578335/creating-php-class-instance-with-a-string
 *
 * $str = "One";
 * $class = "Class".$str;
 * $object = new $class(); <- $object is now an instance of type ClassOne()
 *
 * here's a "variable variable"
 *
 * $personCount = 123;
 * $varname = 'personCount';
 * echo $$varname; // echo's 123
 *
 * using a variable as a method name:
 *
 * $method = 'doStuff';
 * $object = new MyClass();
 * $object->$method(); // calls the MyClass->doStuff() method.
 *
 */
class AspectFactory {
	public static function create($aspect_type = '', $aspect_data = '') {
		$output_object = null;
		if (is_numeric ( $aspect_type )) {
			// a number got passed in, so we'll try to go from aspect_type_id
			
			// aha, it looks like we'll need to look up what the name should be
			// if we get passed an ID only.
			
			$db = Database::get_instance ();
			$sql = "SELECT id, aspect_name FROM aspect_types WHERE id = :id";
			$stmt = $db->prepare ( $sql );
			$stmt->bindParam ( ':id', $aspect_type, PDO::PARAM_STR );
			if ($stmt->execute ()) {
				$row = $stmt->fetchObject ();
				$cleaned_class_name = code_safe_name ( $row->aspect_name );
				$class_name = $cleaned_class_name . 'Aspect';
				if (class_exists ( $class_name )) {
					// a custom class DOES exist, so create one.
					$new_aspect = new $class_name ();
					$new_aspect->aspect_type = $aspect_type;
				} else {
					$new_aspect = new Aspect ();
					$new_aspect->aspect_type = $aspect_type;
				}
			} else {
				return false;
			}
		} else {
			// we got a string, so we'll try to create a custom aspect of that type,
			// if such a class is defined.
			$db = Database::get_instance ();
			$sql = "SELECT id, aspect_name FROM aspect_types WHERE aspect_name = :name";
			$stmt = $db->prepare ( $sql );
			$stmt->bindParam ( ':name', $aspect_type, PDO::PARAM_STR );
			if ($stmt->execute ()) {
				$row = $stmt->fetchObject ();
				$cleaned_class_name = code_safe_name ( $row->aspect_name );
				$class_name = $cleaned_class_name . 'Aspect';
				if (class_exists ( $class_name )) {
					// a custom class DOES exist, so create one.
					$new_aspect = new $class_name ();
					$new_aspect->aspect_type = $row->id;
				} else {
					$new_aspect = new Aspect ();
					$new_aspect->aspect_type = $row->id;
				}
			} else {
				return false;
			}
		}
		
		if ($aspect_data != '') {
			$new_aspect->aspect_data = $aspect_data;
		}
		
		return $new_aspect;
	}
} // end AspectFactory class.

?>