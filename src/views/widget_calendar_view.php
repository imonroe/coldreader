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
$default_controller = $APP ['controller_path'] . '/google_api_controller.php';
$constraint = NULL;
$id = NULL;
if (isset ( $_GET ['constraint'] )) {
	$constraint = trim ( $_GET ['constraint'] );
}
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}

?>

<!-- calendar widget -->
<div class="col-xs-12 col-md-6" id="calendar_widget">
	<h3>On Your Calendar Today</h3>
	<form class="form-inline" id="new_appointment_form">
		<div class="form-group">
			<input name="calenddar_id" type="hidden" value="primary" /> <input
				name="action" type="hidden" value="new_appointment" /> <input
				name="new_appointment_txt" type="text" class="form-control"
				id="new_appointment_text" placeholder="Add a new appointment">
			<button type="submit" class="btn" id="new_appointment_submit">Submit</button>
		</div>
	</form>
	<div id="calendar_stage"></div>
	<script type="text/javascript">
			$(function(){
				
				$("#new_appointment_form").submit(function(event){
					event.preventDefault();
					var fd = $(this).serialize();
					var url = '<?=$default_controller; ?>';
					$.ajax({
							type: 'POST',
							mimeType: 'multipart/form-data',
							url: url,
							data: fd
					})
					.done(function(html){
							$.post( "<?=$default_controller; ?>", { action: "view_calendar" })
								.done(function( data ) {
									$("#calendar_stage").html(data);
								});
							$("#new_appointment_form").trigger("reset");
					});
				});// end task submit
				
				 $.post( "<?=$default_controller; ?>", { action: "view_calendar" })
					  .done(function( data ) {
					  $("#calendar_stage").append(data);
				  });
						  
			});
        </script>
</div>
<!-- end calendar widget -->

