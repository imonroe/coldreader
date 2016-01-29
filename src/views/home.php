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
$task_lists = new GoogleTasksAgent ();

?>
<div id="alerts" class="alert" style="visibility: hidden;"></div>
<div id="home_message" class="col-xs-12">
	<div id="actions">
		<p>
			<a href="index.php?p=form_add_subject">Add a new subject</a>
		</p>
	</div>

    <? include 'src/views/subject_types_list.php'?>
        
</div>