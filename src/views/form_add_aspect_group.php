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
$default_controller = 'src/controllers/aspect_group_controller.php';
$action = 'new_aspect_group';
$form_id = 'new_aspect_group_form';
$id = NULL;
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}
// Dropdowns are here if you need them.
// $dropdowns = new HTMLDropDown;

?>


<form class="form-horizontal" id="<?=$form_id; ?>">
	<fieldset>
		<input name="action" type="hidden" value="<?=$action; ?>" />

		<!-- Form Name -->
		<legend>New aspect group</legend>

		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="group_name">Aspect group
				name</label>
			<div class="col-md-6">
				<input id="group_name" name="group_name" type="text" placeholder=""
					class="form-control input-md"> <span class="help-block">The name of
					the aspect group</span>
			</div>
		</div>

		<!-- Button -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="submit"></label>
			<div class="col-md-4">
				<button id="submit" name="submit" class="btn btn-primary">Submit</button>
			</div>
		</div>

	</fieldset>
</form>


<script type="text/javascript">
    $(function(){
		$("#<?=$form_id; ?>").submit(function(event){
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
					alert(html);
					window.location.replace(document.referrer);	
				});
		});
    });
</script>
