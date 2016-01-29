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
$default_controller = $APP ['controller_path'] . '/aspect_group_controller.php';
$constraint = NULL;
$id = NULL;
if (isset ( $_GET ['constraint'] )) {
	$constraint = trim ( $_GET ['constraint'] );
}
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}
$post_data = 'action: "view_aspect_group", id: "' . $id . '"';

?>

<div class="col-xs-12" id="main_view">
     <?=preloader(); ?>
</div>

<div class="col-xs-12">
	<form class="form-horizontal" id="add_new_aspect_to_group_form">
		<fieldset>
			<input name="aspect_group_id" type="hidden" value="<?=$id; ?>" />
			<!-- Form Name -->
			<legend>Add another aspect type to this group</legend>

			<!-- Text input-->
			<div class="form-group">
				<label class="col-md-4 control-label" for="aspect_name">Aspect name</label>
				<div class="col-md-6">
					<input id="aspect_name" name="aspect_name" type="text"
						placeholder="" class="form-control input-md"> <span
						class="help-block">the name of the aspect to add to this group</span>
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
</div>
<script type="text/javascript">
    $(function(){
        
		$("#aspect_name").autocomplete({
			source: 'src/controllers/aspect_type_controller.php',
			minLength:2	
		});
		
		$.post( "<?=$default_controller; ?>", { <?=$post_data; ?> })
            .done(function( data ) {
            $("#main_view").html(data);
        });
		
		
    });
</script>
