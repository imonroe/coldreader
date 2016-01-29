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
class LogEntry {
	public $id;
	public $message;
	public $time;
	public function __construct($log_message = '') {
		if (! empty ( $log_message )) {
			$this->message = $log_message;
			$this->save ();
		}
	}
	public function __destruct() {
	}
	public function load($id = '') {
		$db = Database::get_instance ();
		if ($id == '') {
			$id = $this->id;
		}
		$query = $db->prepare ( "SELECT * FROM log WHERE id=?" );
		$data = array (
				$id 
		);
		if ($query->execute ( $data )) {
			extract ( $query->fetch () );
			$this->id = $id;
			$this->message = $message;
			$this->time = $time;
			return true;
		} else {
			return false;
		}
	}
	public function save() {
		$db = Database::get_instance ();
		$time_saved = sql_datetime ();
		$query = $db->prepare ( "INSERT INTO log (message, time) VALUES (?, ?)" );
		$data = array (
				$this->message,
				$time_saved 
		);
		if ($query->execute ( $data )) {
			return true;
		} else {
			return false;
		}
	}
	public function print_last_logs($number = '5') {
		$output = '<ul>';
		$db = Database::get_instance ();
		$query = $db->prepare ( "SELECT * FROM log ORDER BY id DESC LIMIT 10" );
		$data = array (
				$number 
		);
		if ($query->execute ( $data )) {
			
			while ( $row = $query->fetch () ) {
				$output .= '<li>' . $row ['message'] . ' - logged at ' . $row ['time'] . '</li>';
			}
		}
		$output .= '</ul>';
		return $output;
	}
} // end LogEntry class.

?>