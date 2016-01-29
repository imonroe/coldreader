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
class Database {
	protected static $db;
	private function __construct() {
		try {
			$app = App::get_instance ();
			self::$db = new PDO ( 'mysql:host=' . $app ['db'] ['server'] . ';dbname=' . $app ['db'] ['database'], $app ['db'] ['username'], $app ['db'] ['password'] );
			self::$db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			self::$db->setAttribute ( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
		} catch ( PDOException $e ) {
			echo "Database error: " . $e->getMessage ();
		}
	}
	public static function get_instance() {
		if (! self::$db) {
			new Database ();
		}
		return self::$db;
	}
} // end Database class.

?>