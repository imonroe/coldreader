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
$current_subject = new Subject ();
$current_aspect_group = new AspectGroup ();
// $default_controller = $APP['controller_path'].'/aspect_controller.php';
$default_controller = 'src/controllers/aspect_controller.php';
$post_data = 'action: "add_aspect_to_subject"';
$id = NULL;
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
	$current_subject->load ( $id );
	$current_aspect_group->load ( $current_subject->get_aspect_group_id () );
}
?>


<form class="form-horizontal" id="new_aspect_form"
	enctype="multipart/form-data">
	<fieldset>


		<!-- Form Name -->
		<legend>Add a new Aspect</legend>


		<input name="subject_id" type="hidden"
			value="<?=$current_subject->id; ?>" /> <input name="aspect_group"
			type="hidden" value="<?=$current_subject->get_aspect_group_id(); ?>" />
		<input name="action" type="hidden" value="add_aspect_to_subject" />

		<!-- Select Basic -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="aspect_type">Type</label>
			<div class="col-md-5">
				<select id="aspect_type" name="aspect_type" class="form-control">
      <?=$current_aspect_group->get_html_dropdown_list(); ?>
    </select> <span class="help-block"><a
					href="index.php?p=form_add_aspect_type&aspect_group=<?=$current_subject->get_aspect_group_id(); ?>">Add
						a new aspect type</a></span>
			</div>
		</div>

		<!-- Textarea -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="aspect_data">Data</label>
			<div class="col-md-6">
				<textarea class="form-control" id="aspect_data" name="aspect_data"
					data-provide="markdown"></textarea>
			</div>

		</div>


		<div class="panel-group" id="accordion" role="tablist"
			aria-multiselectable="true">


			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingOne">
					<h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse"
							data-parent="#accordion" href="#collapseOne"
							aria-expanded="false" aria-controls="collapseOne"> Notes </a>
					</h4>
				</div>
				<div id="collapseOne" class="panel-collapse collapse"
					role="tabpanel" aria-labelledby="headingOne">
					<div class="panel-body">
						<!-- Textarea -->
						<div class="form-group">
							<label class="col-md-4 control-label" for="aspect_notes">Notes</label>
							<div class="col-md-6">
								<textarea class="form-control" id="aspect_notes"
									name="aspect_notes" data-provide="markdown"></textarea>
							</div>
						</div>
						<!-- end txt area -->
					</div>
				</div>
			</div>


			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingTwo">
					<h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse"
							data-parent="#accordion" href="#collapseTwo"
							aria-expanded="false" aria-controls="collapseTwo"> Source </a>
					</h4>
				</div>
				<div id="collapseTwo" class="panel-collapse collapse"
					role="tabpanel" aria-labelledby="headingTwo">
					<div class="panel-body">
						<!-- Text input-->
						<div class="form-group">
							<label class="col-md-4 control-label" for="aspect_source">Source</label>
							<div class="col-md-5">
								<input id="aspect_source" name="aspect_source" type="text"
									placeholder="" class="form-control input-md"> <span
									class="help-block">Where is this data coming from?</span>
							</div>
						</div>
						<!-- end txt -->
					</div>
				</div>
			</div>


			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingThree">
					<h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse"
							data-parent="#accordion" href="#collapseThree"
							aria-expanded="false" aria-controls="collapseThree"> Binary </a>
					</h4>
				</div>
				<div id="collapseThree" class="panel-collapse collapse"
					role="tabpanel" aria-labelledby="headingThree">
					<div class="panel-body">
						<!-- File Button -->
						<div class="form-group">
							<label class="col-md-4 control-label" for="aspect_binary">Binary
								file</label>
							<div class="col-md-4">
								<input id="aspect_binary" name="aspect_binary"
									class="input-file" type="file">
							</div>
						</div>
						<!-- end file button -->
					</div>
				</div>
			</div>


		</div>
		<!-- end collapsable -->

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
		
		$("#new_aspect_form").submit(function(event){
			event.preventDefault();
            //var fd = $(this).serialize();
			var fd = new FormData($(this)[0]);
			var url = '<?=$default_controller; ?>';
			$.ajax({
				type: 'POST',
				mimeType: 'multipart/form-data',
				url: url,
				data: fd,
				cache: false,
        		contentType: false,
        		processData: false
				})
				.done(function(html){
					alert(html);
					window.location.replace(document.referrer);	
				});
		});
		
    });
</script>
