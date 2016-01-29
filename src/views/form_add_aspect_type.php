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
// $default_controller = $APP['controller_path'].'/aspect_controller.php';
$default_controller = 'src/controllers/aspect_controller.php';
$action = 'new_aspect_type';
$form_id = 'new_aspect_type_form';
$id = NULL;
if (isset ( $_GET ['id'] )) {
	$id = ( int ) trim ( $_GET ['id'] );
}

if (isset ( $_GET ['aspect_group'] )) {
	$ag = ( int ) trim ( $_GET ['aspect_group'] );
}
$current_aspect_group = new AspectGroup ();
$current_aspect_group->load ( $ag );
$assigned_aspects_array = $current_aspect_group->get_group_members ();
$unassigned_aspects_array = $current_aspect_group->get_nongroup_members ();
// now we need all the aspects that AREN'T in the group already as an array.

// Dropdowns are here if you need them.
// $dropdowns = new HTMLDropDown;

?>


<div class="col-xs-6 panel" style="height: 400px; overflow: scroll;">
	<h4>Available Aspect Types</h4>

	<ul id="sortable1" class="connectedSortable">
	<? foreach ($unassigned_aspects_array as $k => $v){ ?>
	<li class="ui-state-default" id="<?=$k; ?>"><?=$v; ?></li>
	<? } ?>
</ul>
</div>

<div class="col-xs-6 panel" style="height: 400px; overflow: scroll;">
	<h4>Currently Assigned</h4>

	<ul id="sortable2" class="connectedSortable">
		<li>Currently active</li>
	<? foreach ($assigned_aspects_array as $k => $v){ ?>
	<li class="ui-state-highlight" id="<?=$k; ?>"><?=$v; ?></li>
    <? }?>
</ul>

</div>
<hr / style="clear: both">

<form class="form-horizontal" id="<?=$form_id; ?>">
	<fieldset>
		<input name="action" type="hidden" value="<?=$action; ?>" /> <input
			name="aspect_group" type="hidden" value="<?=$ag; ?>" />
		<!-- Form Name -->
		<legend class="h3">Add a new aspect type to <?=$current_aspect_group->group_name; ?> aspect group</legend>
		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="aspect_name">Aspect name</label>
			<div class="col-md-6">
				<input id="aspect_name" name="aspect_name" type="text"
					placeholder="" class="form-control input-md"> <span
					class="help-block">What is this aspect?</span>
			</div>
		</div>

		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="aspect_description">Description</label>
			<div class="col-md-6">
				<input id="aspect_description" name="aspect_description" type="text"
					placeholder="" class="form-control input-md"> <span
					class="help-block">Describe the aspect</span>
			</div>
		</div>

		<!-- Select Basic -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="selectbasic">Markdown
				formatted?</label>
			<div class="col-md-6">
				<select id="markdown" name="markdown" class="form-control">
					<option value="1">yes</option>
					<option value="0">no</option>
				</select>
			</div>
		</div>

		<!-- Select Basic -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="selectbasic">Is viewable?</label>
			<div class="col-md-6">
				<select id="viewable" name="viewable" class="form-control">
					<option value="1">yes</option>
					<option value="0">no</option>
				</select>
			</div>
		</div>

		<!-- Button -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="submit"></label>
			<div class="col-md-4">
				<button id="submit" name="submit" class="btn btn-primary">Submit</button>
			</div>
		</div>


	</fieldset>
</form>


<script type="text/javascript">
$(function(){
	
	$( "#sortable1, #sortable2" ).sortable({
	  connectWith: ".connectedSortable"
	}).disableSelection();
	
	$("#sortable2").on("sortreceive", function(event, ui){
		//this is what happens when you add a new aspect type.	
		sorted = ui.item.attr('id');
		da = {};
		da['aspect_type_id'] = sorted;
		da['aspect_group_id'] = <?=$current_aspect_group->id ?>;
		da['action'] = 'add_type_to_group';
		$.post('src/controllers/aspect_group_controller.php', da);
	});
	
	$("#sortable2").on("sortremove", function(event, ui){
		//this is what happens when you remove an aspect type.
		sorted = ui.item.attr('id');
		da = {};
		da['aspect_type_id'] = sorted;
		da['aspect_group_id'] = <?=$current_aspect_group->id ?>;
		da['action'] = 'remove_type_from_group';
		$.post('src/controllers/aspect_group_controller.php', da);	
	});
	
	
	$("#<?=$form_id; ?>").submit(function(event){
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
				alert(html);
				window.location.replace(document.referrer);	
			});
	});
});
</script>
