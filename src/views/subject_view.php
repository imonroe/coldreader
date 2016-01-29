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
$relationship_controller = $APP ['controller_path'] . '/relationship_controller.php';
$constraint = NULL;
$id = NULL;
if (isset ( $_GET ['constraint'] )) {
	$constraint = trim ( $_GET ['constraint'] );
}
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}
$post_data = 'action: "view_subject", constraint:"id"';
if ($id) {
	$post_data .= ', id:"' . $id . '"';
}

$current_subject = new Subject ();
$current_subject->load ( ( int ) $id );
// $current_subject->load_aspects();
// $current_subject->load_relationships();

?>



<div class="col-xs-12" id="main_view">
     <?=preloader(); ?>
</div>

<div id="new_relationship_form">
	<h4>New Relationship</h4>
	<form class="form-inline" id="new_rel_form">
		<input type="hidden" id="subject_1_name" name="subject_1_name"
			value="<?=$current_subject->name ?>"> <input type="hidden"
			id="action" name="action" value="add_new_relationship">
		<p><?=$current_subject->name ?> is related to <input type="text"
				id="subject_2_name" name="subject_2_name"
				placeholder="Related subject"> as <input type="text"
				id="relationship_description" name="relationship_description"
				placeholder="Description"> <input type="submit"
				class="btn btn-primary" value="Save" />
		</p>
	</form>
</div>

<script type="text/javascript">
    $(function(){
        $.post( "<?=$default_controller; ?>", { <?=$post_data; ?> })
            .done(function( data ) {
            $("#main_view").html(data);
        });
		
		$("#new_relationship_form").hide();
		
		$("#relationship_anchor").after(function(){
			return document.getElementById("#new_relationship_form");	
		});
		
		$("#new_relationship_form").show();
		
		$("#subject_2_name").autocomplete({
			source: 'src/controllers/search_controller.php',
			minLength:2	
		});
		
		$("#relationship_description").autocomplete({
			source: 'src/controllers/relationship_controller.php',
			minLength:2	
		});
		
		$("#new_rel_form").submit(function(event){
			event.preventDefault();
            var fd = $(this).serialize();
			var url = '<?=$relationship_controller; ?>';
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
