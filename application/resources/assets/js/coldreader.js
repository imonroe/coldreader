// Let's make globally accessible JS go here.


// Initialize any WYSIWYG components on the page.
tinymce.init({
  selector: '.wysiwyg_editor',
  height: 500,
  theme: 'modern',
  plugins: [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
  ],
  toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
  ]
});



/**
 * 
 * 
 *
 * 
 * // Support for Masonry.js
      jQuery.rejigger = function(){
      var Masonry = require('masonry-layout');
      var gutter = (parseInt($('.aspect_display').css('marginBottom')) / 2 );
      var container = document.querySelector('#grid');

      if (!(container == null)){
        var msnry = new Masonry( container, {
          columnWidth: '.aspect_display',
          gutter:gutter,
          transitionDuration: '0.5s',
          stagger: 30
        });
        msnry.reloadItems();
        msnry.layout();
        console.log('layout rejiggered.');
      }
      }
 * 
 * 
 * 
 * 
 *  
 * 


// Here's our master JS on-load trigger.
$(function(){
  // We'll need access to the csrf token, so let's just load it up now.
  let token = document.head.querySelector('meta[name="csrf-token"]');
  if (token) {
      var csrf_code = token.content;
  } else {
      console.error('CSRF token not found.');
  }

  jQuery.display_modal = function(modal_content){
  	var modal_container = $("#modal-container");
  	modal_container.html(modal_content);
  	var dialog = modal_container.dialog({
  			autoOpen: false,
  			height: "auto",
  			width:"auto",
  			modal: true,
  		    draggable: false
  		});
  	dialog.dialog( "open" );
  }

  // Now let's do some stuff.
	// First things first, Let's set up our layout.
	$.rejigger();

	  // Anything that's marked with a confirmation class gets an alert to make sure that we know what we're doing.
	  $('.confirmation').on('click', function () {
        return confirm('Are you sure you want to do that?');
    });



  // Support for drag and drop reordering.
  $(".sortable").sortable({
		handle: '.reorder-handle',
		placeholder: "ui-state-highlight",
		update: function (event, ui) {
			var data = $(this).sortable('serialize');
			data = data + '&_token=' + csrf_code;
			$.post( "/subject/aspect_sorter", data );
			$.rejigger();
		}
	});


	//Support for javascript geolocation
	function get_geographic_coordinates(){
		var current_location = [];
		navigator.geolocation.getCurrentPosition(function(location) {
			current_location["latitude"] = location.coords.latitude;
			current_location["longitude"] = location.coords.longitude;
			current_location["acccuracy"] = location.coords.accuracy;
			document.cookie = "current_latitude="+current_location["latitude"];
			document.cookie = "current_longitude="+current_location["longitude"];
		});
		return current_location;
	}
	//get_geographic_coordinates();

	// Bind a listener to the window resize event, and rejigger the layout if needed.
	jQuery(window).bind('resize', function () {
		$.rejigger();
	});


});
 

*/

