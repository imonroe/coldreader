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


<form id="custom-search-form" class="form-inline" method="get"
	action="index.php?p="
	style="float: left; margin-left: -15px; margin-top: 5px;">
	<input name="action" type="hidden" value="view_subject" /> <input
		name="p" type="hidden" value="search_results_view" />
	<div class="input col-xs-12 col-md-4">
		<input type="text" class="form-control" placeholder="Search"
			id="search_form_field" name="search_form_field">
		<button type="submit" class="btn">Search</button>
	</div>
</form>

<script type="text/javascript">
    $(function(){
		
		$("#search_form_field").autocomplete({
			source: 'src/controllers/search_controller.php',
			minLength:2	
		});
		
        
    });
</script>
