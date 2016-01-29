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
$default_controller = $APP ['controller_path'] . '/search_controller.php';
$google_controller = $APP ['controller_path'] . '/google_api_controller.php';
$constraint = NULL;
$id = NULL;
if (isset ( $_GET ['constraint'] )) {
	$constraint = trim ( $_GET ['constraint'] );
}
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}
if (isset ( $_GET ['search_form_field'] )) {
	$query = urldecode ( $_GET ['search_form_field'] );
}

$post_data = 'action: "get_results", query: "' . $query . '"';
$google_post_data = 'action: "google_search", query: "' . $query . '"';
$google_drive_data = 'action: "google_drive_search", query: "' . $query . '"';

?>

<div class="col-xs-12" id="main_view">
     <?=preloader(); ?>
</div>

<div class="col-xs-12" id="google_drive_results"></div>

<div class="col-xs-12" id="google_results"></div>

<script type="text/javascript">
    $(function(){
        $.post( "<?=$default_controller; ?>", { <?=$post_data; ?> })
            .done(function( data ) {
            $("#main_view").html(data);
        });
		
		$.post( "<?=$google_controller; ?>", { <?=$google_post_data; ?> })
            .done(function( data ) {
			var g_output = data;
            $("#google_results").html(g_output);
        });
		$.post( "<?=$google_controller; ?>", { <?=$google_drive_data; ?> })
            .done(function( data ) {
			var g_output = data;
            $("#google_drive_results").html(g_output);
        });
		
    });
</script>
