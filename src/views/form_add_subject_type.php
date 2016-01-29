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
$default_controller = 'src/controllers/subject_types_controller.php';
$action = 'new_subject_type';
$id = NULL;
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}
// $taxonomy = new Taxonomy;
// $taxonomy->load();

$dropdowns = new HTMLDropDown ();

?>

<form class="form-horizontal" id="new_subject_type_form">
	<fieldset>
		<input name="action" type="hidden" value="<?=$action; ?>" />
		<!-- Form Name -->
		<legend>Add new subject type</legend>

		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="type_name">Name</label>
			<div class="col-md-6">
				<input id="type_name" name="type_name" type="text" placeholder=""
					class="form-control input-md"> <span class="help-block">The name of
					the subject type</span>
			</div>
		</div>

		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="type_description">Description</label>
			<div class="col-md-6">
				<input id="type_description" name="type_description" type="text"
					placeholder="" class="form-control input-md"> <span
					class="help-block">Describe the subject type</span>
			</div>
		</div>

		<!-- Select Basic -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="aspect_group">Aspect Group</label>
			<div class="col-md-6">
				<select id="aspect_group" name="aspect_group" class="form-control">
      <?=$dropdowns->aspect_groups(); ?>
    </select> <span class="help-block"><a
					href="index.php?p=form_add_aspect_group">Add a new aspect group</a></span>
			</div>
		</div>

		<!-- Select Basic -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="parent_id">Parent</label>
			<div class="col-md-6">
				<select id="parent_id" name="parent_id" class="form-control">
					<option value="0">-</option>
      <?=$dropdowns->subject_types(); ?>
    </select> <span class="help-block">If this is a sub-type, select the
					parent here.</span>
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
		$("#new_subject_type_form").submit(function(event){
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
