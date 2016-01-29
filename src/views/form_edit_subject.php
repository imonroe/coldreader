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
$default_controller = 'src/controllers/subject_controller.php';
$post_data = 'action: "edit_subject"';
$id = NULL;
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}
$taxonomy = new Taxonomy ();
$taxonomy->load ();

$current_subject = new Subject ();
$current_subject->load ( $id );

$ddm = new HTMLDropDown ();
?>


<form class="form-horizontal" id="edit_subject_form">
	<fieldset>
		<input name="action" type="hidden" value="edit_subject" /> <input
			name="subject_id" type="hidden" value="<?=$current_subject->id; ?>" />
		<!-- Form Name -->
		<legend>Edit Subject</legend>

		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="name">Subject name</label>
			<div class="col-md-6">
				<input id="name" name="name" type="text" placeholder=""
					class="form-control input-md" value="<?=$current_subject->name; ?>">
				<span class="help-block">The name of the subject</span>
			</div>
		</div>

		<!-- Select Basic -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="subject_type_id">Subject
				type</label>
			<div class="col-md-6">
				<select id="subject_type_id" name="subject_type_id"
					class="form-control">
      <?=$ddm->subject_types(); ?>
    </select>
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

<button id="delete_this_subject" class="btn btn-warning">Delete this
	subject</button>

<script type="text/javascript">
    $(function(){
		// set the dropdown to the current subject.
		$("#subject_type_id").val("<?=$current_subject->subject_type_id; ?>");
		
		$("#edit_subject_form").submit(function(event){
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
		
		$("#delete_this_subject").click(function(event){
			event.preventDefault();
			$.post( "<?=$default_controller; ?>", 
					{ action: "delete_subject", subject_id:"<?=$current_subject->id; ?>"  })
            		.done(function( data ) {
            		alert(data);
					window.location.replace(document.referrer);
        		});
		});
    });
</script>
