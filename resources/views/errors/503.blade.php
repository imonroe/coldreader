<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="icon" href="https://www.ianmonroe.com/wp-content/uploads/2016/09/cropped-skull-32-512-32x32.jpg" sizes="32x32" />
    <link rel="icon" href="https://www.ianmonroe.com/wp-content/uploads/2016/09/cropped-skull-32-512-192x192.jpg" sizes="192x192" />
    <link rel="manifest" href="manifest.json">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Temporarily offline</title>

    <!-- Styles -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


	 <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>



	<script src="//code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
	<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<!-- fontawesome -->
	<script src="https://use.fontawesome.com/c13fe9b096.js"></script>
	<!-- TinyMCE WYSIWYG editor -->
	<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=@php echo( env('TINY_MCE_API_KEY')); @endphp"></script>
	<!-- JQuery UI Touch Punch -->
	<script src="/js/jquery.ui.touch-punch.min.js"></script>

    <!-- dependency: React.js -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-with-addons.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-dom.js"></script>

	<!-- FancyTree -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.23.0/jquery.fancytree-all.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.23.0/skin-bootstrap/ui.fancytree.min.css" rel="stylesheet">


	<!-- my styles should override anything -->
	<link href="{{ asset('css/coldreader.css') }}" rel="stylesheet">


</head>
<body>
	
	<div class="container"> 
		<center> 
			<h2> Site is temporarily offline for maintenance</h2> 
			<p> Please check back shortly. We apologize for the inconvenience. </p>
		</center>

	</div>

</body>
</html>