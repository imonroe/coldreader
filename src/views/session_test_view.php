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
$constraint = NULL;
$id = NULL;
if (isset ( $_GET ['constraint'] )) {
	$constraint = trim ( $_GET ['constraint'] );
}
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}
$post_data = 'action: "view_subject"';

$current_user = new User ();
if (isset ( $APP ['user'] ['email'] )) {
	$current_user->load ( $APP ['user'] ['email'] );
}
$nonce = $_SESSION ['nonce'];

$app = App::get_instance ();
?>

<div class="col-xs-12" id="main_view">

	<pre>
   <? print_r($_SESSION); ?>
   
   current user nonce = <?=$current_user->nonce; ?>
   
   session nonce = <?=$nonce; ?>
   
   verify the nonce: <?=block_cross_site_posting($current_user, $nonce); ?>
   
   <? print_r($_REQUEST)?>
   
   Your IP address is <?=$app['ana']->get_ip(); ?>
   
   </pre>





</div>
<script type="text/javascript">
    $(function(){
       
    });
</script>
