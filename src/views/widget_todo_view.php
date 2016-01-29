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

<!-- todo list widget -->
<div class="col-xs-12 col-md-6" id="todo_list">
	<h3>Today's TODO List</h3>
	<form class="form-inline" id="new_task_form">
		<input name="due" type="hidden" value="<?=$today_timestamp; ?>" /> <input
			name="task_list" type="hidden" value="@default" /> <input
			name="action" type="hidden" value="new_todo_item" /> <input
			name="new_task_title" type="text" class="form-control"
			id="new_task_title" placeholder="Add a new task">
		<button type="submit" class="btn" id="new_task_submit">Submit</button>
	</form>
	<div id="todo_stage" style=""></div>
	<script type="text/javascript">
			$(function(){
				
				$("#new_task_form").submit(function(event){
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
							$.post( "<?=$default_controller; ?>", { action: "view_todo" })
								.done(function( data ) {
									$("#todo_stage").html(data);
								});
							$("#new_task_form").trigger("reset");
					});
				});// end task submit
				
				$.post( "<?=$default_controller; ?>", 
						{ action: "view_todo" })
            			.done(function( data ) {
            			$("#todo_stage").html(data);
        		});
				
			});
			
			function closeTodoItem(item){
				var item_id = item.getAttribute('id');
				$.post( "<?=$default_controller; ?>", 
						{ action: "complete_todo_item", task_id: item_id })
            			.done(function( data ) {
            			$.post( "<?=$default_controller; ?>", 
							{ action: "view_todo" })
							.done(function( data ) {
							$("#todo_stage").html(data);
						});
        		});
					
			}
        </script>
</div>
<!-- end todo list widget -->
