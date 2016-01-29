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
require_once ('../config.php');
if (! isset ( $APP )) {
	die ();
}
// $default_controller = $APP['controller_path'].'/aspect_controller.php';
$app = App::get_instance ();

$default_controller = 'src/controllers/rss_controller.php';
$action = 'new_aspect_group';
$form_id = 'new_aspect_group_form';
$id = NULL;
$requested_feed = false;
$feed_output = false;
$max_count = 5;
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}

if (isset ( $_POST ['feed_url'] )) {
	// if ($app['ana']->is_valid_url($_POST['feed_url'])){
	$requested_feed = trim ( $_POST ['feed_url'] );
	// } else {
	// echo "not a valid feed.";
	// die;
	// }
}

if (isset ( $_POST ['widget_title'] )) {
	$widget_title = trim ( $_POST ['widget_title'] );
}

if (isset ( $_POST ['max_count'] )) {
	$max_count = ( int ) $_POST ['max_count'];
}
// Dropdowns are here if you need them.
// $dropdowns = new HTMLDropDown;

// use PicoFeed\Reader\Reader;

try {
	
	$reader = new PicoFeed\Reader\Reader ();
	$resource = $reader->download ( $requested_feed );
	
	$parser = $reader->getParser ( $resource->getUrl (), $resource->getContent (), $resource->getEncoding () );
	
	$feed_output = $parser->execute ();
	
	// echo $feed;
} catch ( Exception $e ) {
	// echo "Something went wrong with the feed parser.";
}

?>

<? if ($feed_output){ ?>
<div id="rss_widget_<?=$widget_title; ?>" class="news_feed">


	<h4>
		<a href="<?=$feed_output->feed_url; ?>"><?=$feed_output->title; ?></a>
	</h4>

    <?
	$feed_array = ( array ) $feed_output->getItems ();
	?>
 
<ul>
    <?php for($i=0; $i<$max_count; $i++){  ?>
    <li><a href="<?=$feed_array[$i]->url; ?>" target="_blank"><?=$feed_array[$i]->title; ?></a><br />
			<span class="small"><?=standard_date_format($feed_array[$i]->date->getTimestamp()); ?> </span>
		</li>
    <? } ?>
</ul>



</div>
<?php } ?>
    