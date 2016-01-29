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

?>

<!-- clock and welcome widget -->
<div class="col-md-12 col-xs-12" id="clock_widget">
	<span class="small">Welcome back, <?=$APP['user']['name']; ?></span>. <span
		id="inspiration" class="small"></span><br /> <span class="small">It's
		currently <strong><?=date('M j, Y', strtotime('today')); ?><strong>
	
	</span> <span class="small strong" id="time_display"></span>
	<script type="text/javascript">
        	$(function(){
				function startTime() {
					var today = new Date();
					var h = today.getHours();
					var m = today.getMinutes();
					var s = today.getSeconds();
					m = checkTime(m);
					s = checkTime(s);
					document.getElementById('time_display').innerHTML =
					h + ":" + m + ":" + s;
					var t = setTimeout(startTime, 500);
				}
				function checkTime(i) {
					if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
					return i;
				}
				startTime();
				$.post( 
					"<?=$default_controller; ?>", 
					{ action: "inspire_me" })
					.done(function( data ) {
						$("#inspiration").append(data);
					});			
			});
        </script>
</div>
<!-- end clock and welcome widget -->

