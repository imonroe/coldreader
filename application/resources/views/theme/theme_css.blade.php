@php

	$background_img = '/img/background.jpg';
	$navbar_bg_color = '#444';
	$primary_text_color = '#8BFBFF';
	$link_text_color = '#52FF63';
	$border_style = '1px solid #A5FFFF;';

@endphp

/* messing with the theme. */
@import url('https://fonts.googleapis.com/css?family=Abel');

/* include the jquery UI theme css here, since that will want to change depending on the rest of the theme. */
@import url('https://code.jquery.com/ui/1.12.1/themes/dark-hive/jquery-ui.css');

html { 

}

body{ 
	background: url('{{{ $background_img }}}') no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
	color: {{{ $primary_text_color }}};
	font-family: 'Abel', sans-serif;
}

a {
	color:$link_text_color;
}

.navbar{
	background-color:{{{ $navbar_bg_color }}};
	border: {{{ $border_style }}};
}

.navbar-brand a{
	color: {{{ $primary_text_color }}};
}

.panel {
	background-color:{{{ $navbar_bg_color }}};
	border:  {{{ $border_style }}};
	color:{{{ $primary_text_color }}};
	-webkit-box-shadow: 0px 0px 5px 2px rgba(165, 255, 255, .3);
	-moz-box-shadow: 0px 0px 5px 2px rgba(165, 255, 255, .3);
	box-shadow: 0px 0px 5px 2px rgba(165, 255, 255, .3);
}

.panel-body{
	background: url('{{{ $background_img }}}') no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}

.panel-heading, .panel-default > .panel-heading{
	background-color:#444;
	border:{{{ $border_style }}};
	color:{{{ $primary_text_color }}};
	opacity: 0.5;
}

input[type="text"], textarea, button, .btn, select {
  background-color : #444; 
  border: {{{ $border_style }}};
  color: {{{ $primary_text_color }}};
}

input[type="option"]{
	background-color:#000;
	color:{{{ $primary_text_color }}};
}

input[type="checkbox"]{
    background-color: #000;
	color: #666;
}

legend{
	color: {{{ $primary_text_color }}};
}

.sorting-placeholder{
	display:block;
	background-color:#999;
	width:150px;
	height:150px;
}