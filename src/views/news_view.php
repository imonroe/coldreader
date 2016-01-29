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
$default_controller = $APP ['controller_path'] . '/news_controller.php';
$constraint = NULL;
$id = NULL;
if (isset ( $_GET ['constraint'] )) {
	$constraint = trim ( $_GET ['constraint'] );
}
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}
$post_data = 'action: "view_subject"';

$news_feeds = new Subject ();
$news_feeds->load_from_name ( 'News Feeds' );
$feeds_object_array = $news_feeds->to_array ();

$feeds_string = '';
foreach ( $feeds_object_array ['aspects'] as $x ) {
	$feeds_string .= '"' . $x ['aspect_data'] . '",';
}
$feeds_string = rtrim ( $feeds_string, "," );

?>

<div class="col-xs-12" id="main_view">
	<h3>Today's News</h3>
	<div id="feed_display" class="feed_stage" style="padding: 0px;"></div>

</div>
<script type="text/javascript">
  
   $(function() {   
        var feeds = [
                <?=$feeds_string; ?>
        ];
		
        for (index = 0; index < feeds.length; index++) {
                $.ajax({
                type: 'POST',
                mimeType: 'multipart/form-data',
                url: 'src/views/widget_rss_feed_view.php',
                data: {feed_url:feeds[index], max_posts:"5", widget_title:"index_"+index},
                }).done(function(html){
					$("#feed_display").append(html);
					
                });
        }	
    });
	
	

</script>
