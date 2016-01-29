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
?>

<div id="front_page_navigation" class="col-xs-12 col-md-8" sty>
	<a href="index.php?p=dash" class="btn btn-default" style="">Home</a> <a
		href="index.php?p=news_view" class="btn btn-default" style="">News</a>
	<a href="http://www.ianmonroe.com" class="btn btn-default">Blog</a> <a
		href="index.php?p=log_view" class="btn btn-default" style="">Logs</a>
    
    
    <? include 'src/views/main_search_form_view.php'?>

    
</div>