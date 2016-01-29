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
function get_person_details($email_address) {
	$url = $APP ['fullcontact'] ['url'] . '?' . $email_address . '&apiKey=' . $APP ['fullcontact'] ['api_key'];
	$result = file_get_contents ( $url );
	return json_decode ( $result, true );
}
function standard_date_format($timestamp) {
	return date ( 'M j, Y, g:i a T', $timestamp );
}
function sql_datetime($timestamp = '') {
	if ($timestamp == '') {
		$timestamp = time ();
	}
	return date ( "Y-m-d H:i:s", $timestamp );
}
function timestamp($time_string = '') {
	return strtotime ( $time_string );
}
function get_view($view_name) {
	return 'src/views/' . $view_name . '.php';
}
function preloader() {
	return '<div id="preloader" class="text-center center-block"><img src="img/preloader_transparent.gif" /></div>';
}
function create_nonce() {
	$seed_timestamp = date ( "Y-m-d H:i:s" );
	$seed_salt = openssl_random_pseudo_bytes ( 128 );
	$seed = $seed_salt . $seed_timestamp;
	return sha1 ( $seed );
}
function fatal_handler() {
	$error = error_get_last ();
	if ($error !== NULL && $error ['type'] == E_ERROR) {
		$errno = $error ["type"];
		$errfile = $error ["file"];
		$errline = $error ["line"];
		$errstr = $error ["message"];
		new LogEntry ( "Error ($errno) in $errfile on line $errline: $errstr" );
		header ( "HTTP/1.1 500 Internal Server Error" );
	}
}
function is_today($str_date) {
	$today_string = date ( 'M j, Y', time () );
	$target_string = date ( 'M j, Y', strtotime ( $str_date ) );
	if ($today_string == $target_string) {
		return true;
	} else {
		return false;
	}
}
function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
	$sort_col = array ();
	foreach ( $arr as $key => $row ) {
		$sort_col [$key] = $row [$col];
	}
	
	array_multisort ( $sort_col, $dir, $arr );
}
function code_safe_name($string) {
	// we want to take a string like "Custom aspect test"
	// and turn it into "CustomAspectTest"
	// so we can predictably use class names in later code.
	$output = ucwords ( $string );
	$output = preg_replace ( "/[^A-Za-z0-9 ]/", '', $output );
	$output = preg_replace ( '/\s+/', '', $output );
	return $output;
}
function verify_nonce() {
	$app = App::get_instance ();
	$current_user = new User ();
	if (isset ( $app ['user'] ['email'] )) {
		$current_user->load ( $app ['user'] ['email'] );
	}
	$test_nonce = $_SESSION ['nonce'];
	if ($current_user->nonce == $test_nonce) {
		return true;
	} else {
		$message = "Cross-site POST attempt from :" . $app ['ana']->get_ip ();
		new LogEntry ( $message );
		return false;
	}
}
function csfr_protection() {
	// This function should be included in EVERY CONTROLLER FILE.
	// Otherwise, you're just asking for trouble.
	// The goal here is to secure the site against cross-site forgeries.
	// we're using nonces associated with the logged in user to make sure everything's A-OK. If not, we throw a 403 and die.
	$app = App::get_instance ();
	if (verify_nonce ()) {
		return true;
	} else {
		header ( 'HTTP/1.0 403 Forbidden' );
		echo "Your pathetic attempt at cross-site forgery has been detected and logged.  Your IP address is: " . $app ['ana']->get_ip ();
	}
}
function run_parse_loop() {
	$db = Database::get_instance ();
	$right_now = sql_datetime ();
	$yesterday = sql_datetime ( strtotime ( '24 hours ago' ) );
	
	$query = $db->prepare ( "SELECT id, aspect_type FROM aspects WHERE (last_parsed IS NULL) OR (last_parsed < ?)" );
	$data = array (
			$yesterday 
	);
	if ($query->execute ( $data )) {
		while ( $row = $query->fetch () ) {
			$current_aspect = AspectFactory::create ( $row ['aspect_type'] );
			$current_aspect->load ( $row ['id'] );
			$current_aspect->parse ();
			// new LogEntry("Parsed aspect ID: ".$row['id']);
			$current_aspect->last_parsed = sql_datetime ();
			$current_aspect->update ();
		}
	}
}
function find_aspect_from_data($string) {
	$output = false;
	$results_array = array ();
	$db = Database::get_instance ();
	$query = $db->prepare ( "SELECT id FROM aspects WHERE aspect_data like ?" );
	$data = array (
			$string 
	);
	if ($query->execute ( $data )) {
		while ( $row = $query->fetch () ) {
			$results_array [] = $row ['id'];
		}
	}
	if (count ( $results_array ) > 0) {
		$output = $results_array;
	}
	return $output;
}

?>