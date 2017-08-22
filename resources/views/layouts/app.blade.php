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
    <div id="app">
		@if (Auth::check())
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/home') }}">
                       {{ config('app.title') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <div class="btn-group" role="group">
					<ul class="nav navbar-nav">
						<li> <a href="/home" class="btn btn-default">Home</a> </li>
                        <li> <a href="/subject_type" class="btn btn-default">Subject Types</a> </li>
						<li> <a href="/aspect_type" class="btn btn-default">Aspect Types</a> </li>
                    </ul>
					<span style="float:left; margin-left:5px; margin-top:5px;">
					@include('search.search_form')
					</span>
					</div>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <!-- <li><a href="/auth/google/">Login</a></li> -->
							  <li><a href="/login">Login</a></li>
                            <!-- <li><a href="{{ route('register') }}">Register</a></li> -->
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                   <img src="{!!  $user_info['avatar'] !!}" style="width:24px;" />
									{{ Auth::user()->name }}
									<span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
		@else
		<div class="container">
    		<div class="row">
				<h1 style="text-align:center;">{{ env('APP_NAME') }}</h1>
		   </div>
		</div>
		@endif

		<div class="container">
    		<div class="row">

		@if(Session::has('message'))
			<div class="alert alert-info" role="alert">
			{{Session::get('message')}}
			</div>
        @endif

		@if(Session::has('error'))
			<div class="alert alert-danger" role="alert">
			{{Session::get('error')}}
			</div>
        @endif

			<div class="col-md-8 col-md-offset-2">

			</div>

			</div>
		</div>

        @yield('content')
    </div>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}"></script>
		<!-- Custom JS -->
	<script src="{{ asset('js/coldreader.js') }}" type="text/javascript"></script>

</body>
</html>
