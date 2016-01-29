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
$constraint = NULL;
$id = NULL;
if (isset ( $_GET ['constraint'] )) {
	$constraint = trim ( $_GET ['constraint'] );
}
if (isset ( $_GET ['id'] )) {
	$id = trim ( $_GET ['id'] );
}

$post_data = 'action: "list_subjects"';
if (isset ( $constraint )) {
	$post_data .= ', constraint: "' . $constraint . '"';
}
if (isset ( $id )) {
	$post_data .= ', id: "' . $id . '"';
}
?>

<div class="col-xs-12" id="subject_list">
    <?=preloader(); ?>
</div>
<script type="text/javascript">
    $(function(){
        $.post( "<?=$APP['controller_path']; ?>/subject_controller.php", { <?=$post_data; ?> })
            .done(function( data ) {
            $("#subject_list").html(data);
        });
        
    });
</script>
