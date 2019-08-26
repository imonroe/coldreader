@php
    
@endphp

@import  url('{{ $body_font_url }}');
/* include the jquery UI theme css here, since that will want to change depending on the rest of the theme. */
@import  url('{{ $jquery_ui_stylesheet }}');
  
  html { 
  
  }
  
  body{ 
    background: url('/img/background.jpg') no-repeat center center fixed; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    background-color: {{ $background_color }};
    color:{{$primary_font_color}};
    font-family: {!! $body_font_string !!};
    font-size: {{$primary_text_size}};
  }

  .coldreader-background{
    background-color: {{ $background_color }};
  }
  
  a {
      color:{{ $primary_link_color }};
  }
  
  .navbar{
      background-color: {{ $navbar_background_color }};
      border: {{$panel_borders}};
  }
  
  .navbar-brand a{
      color: {{$primary_font_color}};
  }
  
  .panel {
      background-color: {{ $background_color }};
      border:  {{$panel_borders}};
      color:{{$primary_font_color}};
      {{ $border_glow }}
  }
  
  .panel-body{
      background: url('/img/background.jpg') no-repeat center center fixed;
      background-color: {{ $background_color }}; 
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
      word-wrap: break-word;
      border-top: {{$panel_borders}};
  }
  
  .panel-default{
    background-color: {{ $panel_header_background }};
  }

  .panel-default > .panel-header{
      color:{{$primary_font_color}} !important;
      background-color: {{ $panel_header_background }} !important;
      opacity: 0.5;
  }

  .panel-default > .panel-heading{
    color:{{$primary_font_color}} !important;
    background-color: {{ $panel_header_background }} !important;
    opacity: 0.8;
  }

  .panel-controls{
    float:right; 
    margin-right:.5em; 
    clear:none; 
    display:block;
  }
  
  input[type="text"], textarea, button, .btn, select {
    background-color : {{ $background_color }}; 
    border: {{$panel_borders}};
    color: {{$primary_font_color}};
  }
  
  input[type="option"]{
      background-color:{{$input_background_color}};
      color:{{$primary_font_color}};
  }
  
  input[type="checkbox"]{
      background-color: {{$input_background_color}};
      color: {{$primary_font_color}};
  }
  
  legend{
      color: {{$primary_font_color}};
  }

  #page-footer{
    width:100%; 
    background-color: {{ $footer_background_color }}; 
    color: {{ $footer_text_color }}; 
    bottom: 0; 
    padding: 1em; 
    position: relative;
  }

  #page-footer a{
    color: {{ $footer_link_color }};
  }
  
  .sortable-placeholder { 
    background-color: bisque;
  }
  
  .aspect_display{
    display: inline-block;
    min-width:256px;
  }



  /*
  * Here's where we'll put overrides for the Laravel Spark components that need it.
  */

  .modal-body{
      background-color: {{ $background_color }} !important;
  }

  .modal-header{
    background-color: {{ $background_color }} !important;
  }

  .modal-footer{
    background-color: {{ $background_color }} !important;
  }

  .spark-settings-stacked-tabs li a:active, .spark-settings-stacked-tabs li a:hover, .spark-settings-stacked-tabs li a:link, .spark-settings-stacked-tabs li a:visited{
    background-color: {{ $panel_header_background }};
    color:{{ $primary_link_color }}; 
  }


  /*
  * We'll include our overrides for third-party components below.  
  * Wherever possible, we'd like to use our standard styles, but some things don't play nice.
  */

  .vue__datepicker{
    background-color: {{ $background_color }} !important;
  }
