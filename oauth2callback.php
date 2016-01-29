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
require_once ('src/third_party/google-api-php-client-master/src/Google/autoload.php');

session_start ();

$client = new Google_Client ();
$client->setAuthConfigFile ( 'http://' . $_SERVER ['HTTP_HOST'] . '/coldreader/src/data/client_secret.json' );
$client->setRedirectUri ( 'http://' . $_SERVER ['HTTP_HOST'] . '/coldreader/oauth2callback.php' );
$client->setAccessType ( 'offline' );
$client->addScope ( "https://www.googleapis.com/auth/drive" );
$client->addScope ( "https://www.googleapis.com/auth/calendar" );
$client->addScope ( "https://www.googleapis.com/auth/tasks" );
$client->addScope ( "https://www.googleapis.com/auth/userinfo.email" );
$client->addScope ( "https://www.googleapis.com/auth/userinfo.profile" );
$client->addScope ( "https://www.googleapis.com/auth/contacts.readonly" );

if (! isset ( $_GET ['code'] )) {
	$auth_url = $client->createAuthUrl ();
	header ( 'Location: ' . filter_var ( $auth_url, FILTER_SANITIZE_URL ) );
} else {
	$client->authenticate ( $_GET ['code'] );
	$_SESSION ['access_token'] = $client->getAccessToken ();
	$tokens_decoded = json_decode ( $_SESSION ['access_token'] );
	$refresh_token = $tokens_decoded->refresh_token;
	$redirect_uri = 'http://' . $_SERVER ['HTTP_HOST'] . '/coldreader/index.php';
	header ( 'Location: ' . filter_var ( $redirect_uri, FILTER_SANITIZE_URL ) );
}

?>