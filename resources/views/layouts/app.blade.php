@php
	$user_info = session('user_data');
@endphp
<!DOCTYPE html>
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

    <title>{{ config('app.title') }}</title>

    <!-- Styles -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/dark-hive/jquery-ui.css" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


	 <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>


	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

	<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

	<!-- fontawesome -->
	<script src="https://use.fontawesome.com/c13fe9b096.js"></script>

	<!-- TinyMCE WYSIWYG editor -->
	<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=@php echo( env('TINY_MCE_API_KEY')); @endphp"></script>
	<!-- JQuery UI Touch Punch -->
	<script src="/js/jquery.ui.touch-punch.min.js"></script>

	<!-- stylesheet -->
    <link href="/js/literally_canvas/_assets/literallycanvas.css" rel="stylesheet">

    <!-- dependency: React.js -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-with-addons.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-dom.js"></script>

    <!-- Literally Canvas -->
    <script src="/js/literally_canvas/_js_libs/literallycanvas.js"></script>

	<!-- FancyTree -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.23.0/jquery.fancytree-all.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.23.0/skin-bootstrap/ui.fancytree.min.css" rel="stylesheet">

	<!-- Custom JS -->
	<script src="{{ asset('js/coldreader.js') }}?@php echo(time()); @endphp" type="text/javascript"></script>


	<!-- my styles should override anything -->
	<link href="{{ asset('css/coldreader.css') }}?@php echo(time()); @endphp" rel="stylesheet">


</head>
<body>
    <div id="application-container">
		@if (Auth::check())
			@include('layouts.navbar')
		@else
		<div class="container">
    		<div class="row">
				<h1 style="text-align:center;">{{ env('APP_NAME') }}</h1>
		   </div>
		</div>
		@endif

		<div class="container-fluid">
    		<div class="row">

				@if(Session::has('message'))
				<div class="mx-auto col-xs-12 col-lg-8 alert alert-info alert-dismissible fade show" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
  					</button>
					<h5 class="text-center">{{Session::get('message')}}</h5>
				</div>
				@endif

				@if(Session::has('error'))
				<div class="mx-auto col-xs-12 col-lg-8 alert alert-danger alert-dismissible fade show" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
  					</button>
					<h5 class="text-center">{{Session::get('error')}}</h5>
				</div>
				@endif

				<div id="main_page_panel" class="panel panel-default" style="margin:1em; width:100%">
					@yield('content')	
				</div>

			</div>

    </div>
	</div> <!-- end application-container" -->
	
	<div id="modal-container" style="display:none;"> x </div>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
