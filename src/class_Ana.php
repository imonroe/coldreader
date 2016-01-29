<?php

/*
 *
 * Ana utility class
 * Version: 0.1 beta
 * Author: Ian Monroe
 * Original create date: 11/6/2015
 * Last significant update: 11/6/2015
 *
 * This is a class of useful shortcuts, functions I often find myself needing,
 * aliases for common commands that I don't want to keep looking up,
 * and so forth.
 *
 * Consider it a personal utility library that I plan to use across projects to make
 * my life easier.
 *
 * Written with PHP 5.x in mind. Should require no dependencies, other than
 * what you'd find in a standard LAMP stack PHP implementation. (cURL, etc.)
 *
 * The name of this library derives from the word "Ana", defined as:
 * "A collection of miscellaneous information about a particular subject, person, place, or thing."
 *
 *
 */
class Ana {
	function __construct() {
	}
	function __destruct() {
	}
	
	// /////////////////////////////////////////////////////////////////
	/* Date, time functions in this section. */
	function standard_date_format($timestamp) {
		// My preferred datetime format for presentation
		if ($timestamp == '') {
			$timestamp = time ();
		}
		return date ( 'M j, Y, g:i a T', $timestamp );
	}
	function sql_datetime($timestamp = '') {
		// Returns an MySQL-friendly datetime string.
		if ($timestamp == '') {
			$timestamp = time ();
		}
		return date ( "Y-m-d H:i:s", $timestamp );
	}
	function google_datetime($timestamp = '') {
		// Google likes RFC3339-style datetimes
		if ($timestamp == '') {
			$timestamp = time ();
		}
		return date ( DATE_RFC3339, $timestamp );
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
	function sooner_than($date_string) {
		if ((strtotime ( "now" )) < (strtotime ( $date_string ))) {
			return true;
		} else {
			return false;
		}
	}
	function later_than($date_string) {
		if ((strtotime ( "now" )) > (strtotime ( $date_string ))) {
			return true;
		} else {
			return false;
		}
	}
	function print_relative_date($date) {
		$valid_date = (is_numeric ( $date ) && strtotime ( $date ) === FALSE) ? $date : strtotime ( $date );
		$diff = time () - $valid_date;
		if ($diff > 0) {
			if ($diff < 60) {
				return $diff . " second" . $this->_plural ( $diff ) . " ago";
			}
			$diff = round ( $diff / 60 );
			
			if ($diff < 60) {
				return $diff . " minute" . $this->_plural ( $diff ) . " ago";
			}
			$diff = round ( $diff / 60 );
			
			if ($diff < 24) {
				return $diff . " hour" . $this->_plural ( $diff ) . " ago";
			}
			$diff = round ( $diff / 24 );
			
			if ($diff < 7) {
				return "about " . $diff . " day" . $this->_plural ( $diff ) . " ago";
			}
			$diff = round ( $diff / 7 );
			
			if ($diff < 4) {
				return "about " . $diff . " week" . $this->_plural ( $diff ) . " ago";
			}
			
			return "on " . date ( "F j, Y", $valid_date );
		} else {
			if ($diff > - 60) {
				return "in " . - $diff . " second" . $this->_plural ( $diff );
			}
			$diff = round ( $diff / 60 );
			
			if ($diff > - 60) {
				return "in " . - $diff . " minute" . $this->_plural ( $diff );
			}
			$diff = round ( $diff / 60 );
			
			if ($diff > - 24) {
				return "in " . - $diff . " hour" . $this->_plural ( $diff );
			}
			$diff = round ( $diff / 24 );
			
			if ($diff > - 7) {
				return "in " . - $diff . " day" . $this->_plural ( $diff );
			}
			$diff = round ( $diff / 7 );
			
			if ($diff > - 4) {
				return "in " . - $diff . " week" . $this->_plural ( $diff );
			}
			
			return "on " . date ( "F j, Y", $valid_date );
		}
	}
	
	/* end of Date and Time functions */
	// /////////////////////////////////////////////////////////////////
	/* Error handling functions in this section */
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
	
	/* end of error handling functions */
	// /////////////////////////////////////////////////////////////////
	/* Array manipulation functions in this section */
	function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
		$sort_col = array ();
		foreach ( $arr as $key => $row ) {
			$sort_col [$key] = $row [$col];
		}
		array_multisort ( $sort_col, $dir, $arr );
	}
	function object_to_array($object) {
		if (! is_object ( $object ) && ! is_array ( $object ))
			return $object;
		return array_map ( 'objectToArray', ( array ) $object );
	}
	
	/* end of Array manipulation functions */
	// /////////////////////////////////////////////////////////////////
	/* String manipulation functions in this section */
	function word_limit($haystack, $ubound) {
		$return_val = explode ( " ", $haystack );
		return implode ( " ", array_splice ( $return_val, 0, $ubound ) );
	} // end function word_limit
	function convert_to_utf($input) {
		$avail_encodings = '';
		$enc_array = mb_list_encodings ();
		foreach ( $enc_array as $enc ) {
			$avail_encodings = $avail_encodings . $enc . ", ";
		}
		$avail_encodings = substr ( $avail_encodings, 0, - 2 );
		return mb_convert_encoding ( $input, "UTF-8", $avail_encodings );
	}
	function plain_text($input) {
		// takes a string, strips out tags, HTML entities, etc. Returns plain UTF-8 string.
		// must be used in a template with PHP parsed on OUTPUT, not on input.
		$output = strip_tags ( $input );
		$output = str_replace ( "&nbsp;", " ", $output );
		$output = html_entity_decode ( $output, ENT_COMPAT, 'UTF-8' );
		return $output;
	}
	function trim_string_to_length($str, $len) {
		// limits to a length; doesn't pretty it up at all.
		return mb_strimwidth ( $str, 0, $len );
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
	
	/* end of string manipulation functions */
	// /////////////////////////////////////////////////////////////////
	/* Numeric manipulation functions go here */
	function even_or_odd($number) {
		$int_number = cint ( $number );
		$return_val = '';
		if ($int_number < 0) {
			// normalize negative numbers.
			$int_number = $int_number * - 1;
		}
		if ($int_number = 0) {
			$return_val = false;
		} elseif ($int_number = 1) {
			$return_val = 'odd';
		} elseif ($int_number > 1) {
			if ($int_number % 2) {
				$return_val = 'odd';
			} else {
				$return_val = 'even';
			}
		} else {
			return 'even_odd error';
		}
		return $return_val;
	} // end even_or_odd
	function random_number($lowbound = 1, $highbound = 100) {
		// returns a random integer between the low bound and the high bound.
		// default range is 1-100
		// Should be suitible for cryptographically secure random number generation.
		// see: http://php.net/manual/en/function.random-int.php
		return random_int ( $lowbound, $highbound );
	}
	function random_hex($bytes = 8) {
		// returns a hex value corresponding with the given number of random bytes.
		// cryptographically secure. Good for seeds and salts, etc.
		$r_bytes = random_bytes ( $bytes );
		return bin2hex ( $r_bytes );
	}
	
	/* end numeric manipulation functions */
	// /////////////////////////////////////////////////////////////////
	/* Uncategorized functions in this section */
	function create_nonce() {
		$seed_timestamp = date ( "Y-m-d H:i:s" );
		$seed_salt = openssl_random_pseudo_bytes ( 128 );
		$seed = $seed_salt . $seed_timestamp;
		return sha1 ( $seed );
	}
	function current_page_url() {
		$pageURL = 'http';
		$pageURL .= "://";
		$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
		return $pageURL;
	}
	function get_url_segment($number) {
		$output = false;
		$page_url = $_SERVER ['REQUEST_URI'];
		
		if (strpos ( $page_url, '?' )) {
			$page_url = strtok ( $page_url, '?' );
		}
		
		$url_array = explode ( "/", $page_url );
		$arr_len = count ( $url_array );
		if ($number <= ($arr_len - 1)) {
			$output = mysql_real_escape_string ( $url_array [$number] );
		}
		return $output;
	}
	function is_valid_link($link) {
		// Feed it a URL, returns an HTTP status code.
		// swiped from here: http://www.codezuzu.com/2015/03/how-to-validate-linkurl-in-php/
		$ch = curl_init ( $link );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt ( $ch, CURLOPT_HEADER, TRUE ); // Include the headers
		curl_setopt ( $ch, CURLOPT_NOBODY, TRUE ); // Make HEAD request
		$response = curl_exec ( $ch );
		if ($response === false) {
			// something went wrong, assume not valid
			return false;
		}
		$http_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		if (in_array ( $http_code, array (
				200,
				301,
				302,
				303,
				307 
		) ) === false) {
			// not a valid http code to asume success, link is not valid
			return false;
		}
		curl_close ( $ch );
		return $http_code;
	}
	function get_ip() {
		// swiped from here: https://www.chriswiegman.com/2014/05/getting-correct-ip-address-php/
		/*
		 * The goal here is to get the actual IP address of the requester, even behind a reverse proxy, etc.
		 */
		// Just get the headers if we can or else use the SERVER global
		if (function_exists ( 'apache_request_headers' )) {
			$headers = apache_request_headers ();
		} else {
			$headers = $_SERVER;
		}
		// Get the forwarded IP if it exists
		if (array_key_exists ( 'X-Forwarded-For', $headers ) && filter_var ( $headers ['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )) {
			$the_ip = $headers ['X-Forwarded-For'];
		} elseif (array_key_exists ( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var ( $headers ['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )) {
			$the_ip = $headers ['HTTP_X_FORWARDED_FOR'];
		} else {
			
			$the_ip = filter_var ( $_SERVER ['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
		}
		return $the_ip;
	}
	function submit_post_request($url, $data) {
		// swiped from:
		// http://stackoverflow.com/questions/5647461/how-do-i-send-a-post-request-with-php
		// use key 'http' even if you send the request to https://...
		/*
		 * $options = array(
		 * 'http' => array(
		 * 'header' => "Content-type: application/x-www-form-urlencoded\r\n",
		 * 'method' => 'POST',
		 * 'content' => http_build_query($data),
		 * ),
		 * );
		 * //new LogEntry(var_dump($options));
		 * $context = stream_context_create($options);
		 * $result = file_get_contents($url, false, $context);
		 */
		echo print_r ( $data );
		$fields_string = '';
		foreach ( $data as $key => $value ) {
			$fields_string .= $key . '=' . $value . '&';
		}
		rtrim ( $fields_string, '&' );
		echo '------' . $fields_string;
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, count ( $data ) );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields_string );
		$result = curl_exec ( $ch );
		
		return $result;
	}
	
	/* end of uncategorized functions */
	// /////////////////////////////////////////////////////////////////
} // end of the Aaa Library.

?>