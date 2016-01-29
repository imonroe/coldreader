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
if (! isset ( $APP )) {
	die ();
}
$default_controller = $APP ['controller_path'] . '/subject_controller.php';
$constraint = NULL;
$id = NULL;
if (isset ( $_GET ['constraint'] )) {
	$constraint = trim ( $_GET ['constraint'] );
}
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}
$post_data = 'action: "view_subject"';
$suspicious_address = $APP ['ana']->get_ip ();

$log_message = 'Unauthorized access from Google Account: ' . $APP ['user'] ['email'] . ' at IP address: ' . $suspicious_address;
$log_entry = new LogEntry ( $log_message );
$log_entry->save ();

?>

<div class="col-xs-12" id="main_view">
	<h2>You are not authorized to use this software.</h2>
	<p> Your Google account is: <?=$APP['user']['email']; ?></p>
	<p>This unauthorized use has been logged.</p>
</div>

