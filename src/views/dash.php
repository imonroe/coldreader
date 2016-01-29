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
$default_controller = $APP ['controller_path'] . '/dash_controller.php';
$constraint = NULL;
$id = NULL;
if (isset ( $_GET ['constraint'] )) {
	$constraint = trim ( $_GET ['constraint'] );
}
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}
$post_data = 'action: "view_dash"';
$today_timestamp = date ( DATE_RFC3339, strtotime ( 'yesterday 11:59PM' ) );
?>

<div class="col-xs-12" id="main_view">
    
    <? //include 'src/views/widget_todo_view.php' ?>
    
    <? //include 'src/views/widget_calendar_view.php' ?>
    
    <? //include 'src/views/widget_shopping_list_view.php' ?>
    
    
    
    <hr style="border: 1px solid white; clear: both; width: 100%" />
	<h3>Your Knowledge Base</h3>
	<!-- Knowledge base listing -->
	<div id="home_message" class="col-xs-12">
		<div id="actions">
			<p>
				<a href="index.php?p=form_add_subject">Add a new subject</a>
			</p>
		</div>
        <? include 'src/views/subject_types_list.php' ?>    
	</div>
	<!-- end knowledge base listing -->

</div>
<script type="text/javascript">
    $(function(){
		
		//refresh the page every 5 minutes, unless there has been activity
        var time = new Date().getTime();
		 $(document.body).bind("mousemove keypress", function(e) {
			 time = new Date().getTime();
		 });
	
		 function refresh() {
			 if(new Date().getTime() - time >= 300000) 
				 window.location.reload(true);
			 else 
				 setTimeout(refresh, 10000);
		 }
	
		 setTimeout(refresh, 10000);
    });
</script>
