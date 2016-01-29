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
require_once ('src/config.php');

// a little basic routing.
$page = 'dash';
if (isset ( $_GET ['p'] )) {
	$page = trim ( $_GET ['p'] );
}

if (isset ( $_GET ['logout'] )) {
	// delete the session auth session so you have to go back through google authentication.
	unset ( $_SESSION ['access_token'] );
	header ( 'Location: https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['PHP_SELF'] );
}

$current_user = new User ();
if (isset ( $APP ['user'] ['email'] )) {
	$current_user->load ( $APP ['user'] ['email'] );
	$current_user->set_nonce ();
	$_SESSION ['nonce'] = $current_user->nonce;
}

if (! $current_user->is_logged_in ()) {
	$page = 'loggedout';
}

run_parse_loop ();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<link rel="manifest" href="manifest.json">
<title><?=$APP['name']; ?></title>

<!-- jQuery (necessary for Bootstrap JavaScript plugins) -->
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<link rel="stylesheet"
	href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<script
	src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<!-- Bootstrap -->
<link rel="stylesheet"
	href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<script
	src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<link
	href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css"
	rel="stylesheet">
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<!-- wysiwyg -->
<link href="src/third_party/wysiwyg/editor.css" type="text/css"
	rel="stylesheet" />
<script src="src/third_party/wysiwyg/editor.js"></script>
<script type="text/javascript"
	src="http://www.ianmonroe.com/frontpage/src/FeedEk.js"></script>
<script type="text/javascript"
	src="http://www.ianmonroe.com/frontpage/src/masonry.js"></script>


<!-- markdown -->
<link
	href="src/third_party/bootstrap_markdown/css/bootstrap-markdown.min.css"
	type="text/css" rel="stylesheet" />
<script
	src="src/third_party/bootstrap_markdown/js/bootstrap-markdown.js"></script>

<!-- my bullshit theme -->
<link href="assets/styles.css" type="text/css" rel="stylesheet" />

<script type="text/javascript">
	$(function(){
		
		
	});
    </script>

<link rel="stylesheet" href="assets/styles.css" type="text/css">

</head>
<body>
	<div class="container">

		<div class="col-xs-12">
			<span class="logo"><a href="index.php"><?=$APP['name']; ?></a></span>
			<span class="col-xs-4 pull-right small" id="auth">
				<p>Logged in as <?=$APP['user']['email']; ?> | <a
						href="index.php?logout=true">Log out</a>
				</p>
			</span>
		</div>
        
        
        
            <? include 'src/views/navbar.php'?>
            
            <? include 'src/views/widget_clock_welcome_view.php'?>
            
            
    
        <hr style="border: 1px solid white; clear: both; width: 100%" />

		<div class="row">          
          
              <? include 'src/views/' . $page . '.php'; ?>
         
        </div>

		<div id="footer"></div>
	</div>
	<!-- end main container -->

</body>

</html>
