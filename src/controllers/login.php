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

// bootstrap the rest of the codebase.
require_once ('../config.php');
$db = Database::get_instance ();
function PersonaVerify() {
	$url = 'https://verifier.login.persona.org/verify';
	
	$assert = filter_input ( INPUT_POST, 'assertion', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
	
	$scheme = 'http';
	if (isset ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] != "on") {
		$scheme = 'https';
	}
	$audience = sprintf ( '%s://%s:%s', $scheme, $_SERVER ['HTTP_HOST'], $_SERVER ['SERVER_PORT'] );
	
	$params = 'assertion=' . urlencode ( $assert ) . '&audience=' . urlencode ( $audience );
	
	$ch = curl_init ();
	$options = array (
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => 2,
			CURLOPT_POSTFIELDS => $params 
	);
	
	curl_setopt_array ( $ch, $options );
	$result = curl_exec ( $ch );
	curl_close ( $ch );
	return $result;
}

// Call the BrowserID API
$response = PersonaVerify ();

// If the authentication is successful set the auth cookie
$result = json_decode ( $response, true );
if ('okay' == $result ['status']) {
	$email = $result ['email'];
	setcookie ( 'auth', $email, time () + 3600, '/' );
	$current_user = new User ();
	if (! $current_user->check_username ( $email )) {
		// $current_user->email_address = $email;
		// $current_user->create();
		// $current_user->set_nonce();
		// setcookie('nonce', $current_user->nonce, time()+3600, '/');
	} else {
		$current_user->load ( $email );
		$current_user->set_nonce ();
		setcookie ( 'nonce', $current_user->nonce, time () + 3600, '/' );
	}
}

// Print the response to the Ajax script
echo $response;

?>