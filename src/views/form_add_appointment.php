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

// $default_controller = $APP['controller_path'].'/aspect_controller.php';
$default_controller = 'src/controllers/dash_controller.php';
$action = 'new_aspect_group';
$form_id = 'new_appointment_form';
$id = NULL;
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}
// Dropdowns are here if you need them.
// $dropdowns = new HTMLDropDown;

$today_timestamp = date ( DATE_RFC3339, strtotime ( 'today 11:59PM' ) );

?>


<form class="form-inline" id="<?=$form_id; ?>">
	<input name="calendar" type="hidden" value="primary" /> <input
		name="action" type="hidden" value="new_appointment" />
	<div class="form-group">
		<label class="sr-only" for="new_appointment_title">New Appointment</label>
		<input type="text" class="form-control" id="new_task_title"
			placeholder="Add a new appointment">
	</div>
	<button type="submit" class="btn" id="new_appointment_submit">Submit</button>
</form>



