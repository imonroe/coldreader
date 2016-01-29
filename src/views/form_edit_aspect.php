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
$current_aspect = new Aspect ();
$default_controller = 'src/controllers/aspect_controller.php';
$action = 'edit_aspect';
$form_id = 'edit_aspect_form';
$id = NULL;
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
	$current_aspect->load ( $id );
}
// $current_aspect_group = new AspectGroup;
// $current_aspect_group->load($current_aspect->return_aspect_group_id());
// Dropdowns are here if you need them.
// $dropdowns = new HTMLDropDown;
?>


<form class="form-horizontal" id="<?=$form_id; ?>">
	<fieldset>

		<!-- Form Name -->
		<legend>Edit this aspect</legend>

		<input name="action" type="hidden" value="<?=$action; ?>" /> <input
			name="aspect_id" type="hidden" value="<?=$current_aspect->id; ?>" />
		<input name="aspect_type" type="hidden"
			value="<?=$current_aspect->return_aspect_type_id(); ?>">

		<!-- Select Basic -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="aspect_type">Type</label>
			<div class="col-md-5">

				<!-- <select id="aspect_type" name="aspect_type" class="form-control">
      <? //=$current_aspect_group->get_html_dropdown_list(); ?>
    </select> -->
				<p><?=$current_aspect->return_aspect_type_name(); ?></p>

			</div>
		</div>

		<!-- Textarea -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="aspect_data">Data</label>
			<div class="col-md-6">
				<textarea class="form-control" id="aspect_data" name="aspect_data"
					data-provide="markdown"><?=$current_aspect->aspect_data; ?></textarea>
			</div>
		</div>

		<!-- Textarea -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="aspect_notes">Notes</label>
			<div class="col-md-6">
				<textarea class="form-control" id="aspect_notes" name="aspect_notes"
					data-provide="markdown"><?=$current_aspect->aspect_notes; ?></textarea>
			</div>
		</div>

		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="aspect_source">Source</label>
			<div class="col-md-5">
				<input id="aspect_source" name="aspect_source" type="text"
					class="form-control input-md"
					value="<?=$current_aspect->aspect_source; ?>"> <span
					class="help-block">Where is this data coming from?</span>
			</div>
		</div>

		<!-- File Button -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="aspect_binary">Binary file</label>
			<div class="col-md-4">
				<input id="aspect_binary" name="aspect_binary" class="input-file"
					type="file">
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

<button id="delete_this_aspect" class="btn btn-warning">Delete this
	aspect</button>

<script type="text/javascript">
    $(function(){
		//set the dropdown list to the current setting on page load.
		$("#aspect_type").val(<?=$current_aspect->aspect_type; ?>);
		
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
		
		$("#delete_this_aspect").click(function(event){
			event.preventDefault();
			$.post( "<?=$default_controller; ?>", 
					{ action: "delete_aspect", aspect_id:"<?=$current_aspect->id; ?>"  })
            		.done(function( data ) {
            		alert(data);
					window.location.replace(document.referrer);
        		});
		});
		
    });
</script>
