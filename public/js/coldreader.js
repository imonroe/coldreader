$(function(){

	$('.confirmation').on('click', function () {
        return confirm('Are you sure you want to do that?');
    });

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

	//handle autocompletion
	$( ".subject-autocomplete" ).autocomplete({
		source: '/subject/autocomplete',
		minLength:2,
		select: function(event, ui) {
			$(this).val(ui.item.value);
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
	get_geographic_coordinates();


});

