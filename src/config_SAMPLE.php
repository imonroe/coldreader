<?php

/**
 * Coldreader configuration file.
 *
 * This is the primary way to set up the Coldreader app.
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
ini_set ( 'display_errors', 'On' );
$APP = array ();
$APP ['debug'] = false;

// Configure these for your particular situation
define ( '__ROOT__', '/var/www/coldreader/' );
$APP ['http_path'] = 'http://www.webserver.com/coldreader';
$APP ['root_path'] = '/var/www/coldreader';
$APP ['admin_email'] = 'your@email.com';

$APP ['db'] ['server'] = 'localhost';
$APP ['db'] ['database'] = 'DB SERVER';
$APP ['db'] ['username'] = 'DB_USER';
$APP ['db'] ['password'] = 'DB_PASSWORD';

/*
 * For the database schema that the app is expecting,
 * check out the file called "database_schema.txt" in the
 * root directory of the app.
 */

// API settings
$APP ['fullcontact'] ['api_key'] = 'XXXXXXXXXXXXXXXXX';
$APP ['fullcontact'] ['url'] = 'https://api.fullcontact.com/v2/person.json';
$APP ['aylien'] ['api_key'] = 'XXXXXXXXXXXXXXXXX';
$APP ['aylien'] ['app_id'] = 'XXXXXXXXX';
$APP ['aylien'] ['endpoint'] = 'https://api.aylien.com/api/v1';
$APP ['mashape'] ['api_key'] = 'XXXXXXXXXXXXXXXXX';
$APP ['google'] ['application_name'] = 'XXXXXXXXX';
$APP ['google'] ['public_api_key'] = 'XXXXXXXXXXXXXXXXX';
$APP ['google'] ['client_secret'] = '{XXXXX}';
$APP ['google'] ['maps'] ['api_key'] = 'XXXXXXXXXXXX';
$APP ['google'] ['custom_search'] ['api_key'] = 'XXXXXXXXXXXXXXX';
$APP ['google'] ['custom_search'] ['cz'] = 'XXXXXXXXXXXXXX';

// basic settings
$APP ['name'] = 'Coldreader';
$APP ['author'] = 'Ian Monroe';
$APP ['version'] = '0.1';
$APP ['src_path'] = __ROOT__ . 'src';
$APP ['view_path'] = $APP ['src_path'] . '/views';
$APP ['model_path'] = $APP ['src_path'] . '/models';
$APP ['controller_path'] = $APP ['http_path'] . '/src/controllers';
$APP ['uploads_path'] = $APP ['http_path'] . '/assets/uploaded/';
$APP ['upload_src_path'] = $APP ['http_path'] . '/assets/uploaded/';
$APP ['user-agent'] = 'ExperimentalEngine (' . $APP ['root_path'] . '; ' . $APP ['admin_email'] . ')';

// Load additional files
require_once ('lib.php'); // this stuff should get replace by Ana, mostly. See below.
                         
// Require all the models, so our data structures make sense.
foreach ( glob ( $APP ['src_path'] . "/models/*.php" ) as $filename ) {
	require_once $filename;
}

// Ana is my helper library, so we'll add her separately.
require_once ('class_Ana.php');
// load Ana, and put her in the app array for global access.
$ana = new Ana ();
$APP ['ana'] = $ana;

/* Third-party libraries should be included here. ----------------------------------------- */
require_once ('third_party/parsedown-master/Parsedown.php');
require_once ('third_party/class.upload.php-master/src/class.upload.php');
// for usage on that: https://github.com/verot/class.upload.php

// Google API autoload:
require_once ($APP ['src_path'] . '/third_party/google-api-php-client-master/src/Google/autoload.php');

// Snoopy class
require_once ($APP ['src_path'] . '/third_party/Snoopy.class.php');

// Composer Autoloader:
require __ROOT__ . '/vendor/autoload.php';

/* end third party libraries ----------------------------------------------------------------- */

// Initialize the Google client with an oAuth call.
session_start ();

$client = new Google_Client ();
$client->setAuthConfig ( $APP ['google'] ['client_secret'] );
$client->setScopes ( array (
		"https://www.googleapis.com/auth/drive",
		"https://www.googleapis.com/auth/calendar",
		"https://www.googleapis.com/auth/contacts.readonly",
		"https://www.googleapis.com/auth/tasks",
		"https://www.googleapis.com/auth/userinfo.email",
		"https://www.googleapis.com/auth/userinfo.profile" 
) );

if (isset ( $_SESSION ['access_token'] ) && $_SESSION ['access_token']) {
	$client->setAccessToken ( $_SESSION ['access_token'] );
	if ($client->isAccessTokenExpired ()) {
		$authUrl = $client->createAuthUrl ();
		header ( 'Location: ' . filter_var ( $authUrl, FILTER_SANITIZE_URL ) );
	}
} else {
	$redirect_uri = 'http://' . $_SERVER ['HTTP_HOST'] . '/coldreader/oauth2callback.php';
	header ( 'Location: ' . filter_var ( $redirect_uri, FILTER_SANITIZE_URL ) );
}

// Store the google client in the App array so we can access it elsewhere.
$APP ['google'] ['client'] = $client;
// we're finished with the google client, and we've stored it in our app array.

// let's see if we can get an email address from google, and confirm that you're good to go.
$user_info_service = new Google_Service_Plus ( $APP ['google'] ['client'] );
$user_info = $user_info_service->people->get ( "me" );
$APP ['user'] ['name'] = $user_info ['name'] ['givenName'];
$APP ['user'] ['email'] = $user_info ['emails'] [0] ['value'];

// Initialize the app.
$app = App::get_instance ( $APP );
register_shutdown_function ( "fatal_handler" );
?>
