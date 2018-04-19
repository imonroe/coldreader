<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
     
 
     <!-- CSS -->
     <link href="/css/sweetalert.css" rel="stylesheet">
     <link href="/css/jquery-ui.min.css" rel="stylesheet">
     <link href="/css/tinymce_skins/lightgray/skin.min.css" rel="stylesheet">
     <link href="/css/bootstrap.min.css" rel="stylesheet">

     <link href="{{ mix('css/app.css') }}" rel="stylesheet">
     

     <!-- Scripts -->
     <script src="/js/tinymce/tinymce.min.js"></script>
     @yield('scripts', '')



    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>

    
    @include('timezone_agent::timezones')

    <!-- theme -->
    <link href="/cr_theme/css?t={{ time() }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        
        <!-- navbar -->
        @include('navigation.navbar2')

        <!-- main content -->
        <main class="py-4">
            @yield('content')
        </main>
    </div>

     <!-- JavaScript -->
     <script src="{{ mix('js/app.js') }}"></script>
     <!-- <script src="/js/jquery-ui.min.js"></script> -->
     <!-- <script src="/js/jquery.ui.touch-punch.min.js"></script> -->
     <script src="/js/sweetalert.min.js"></script>
     <script src="/js/coldreader.js"></script>
     <script src="/js/bootstrap.min.js"></script>

</body>
</html>
