<div class="widget">
	<h3>Today's Calendar</h3>

	<form class="form-inline" id="new_appointment_form">
          <div class="form-group">
          <input name="calenddar_id" type="hidden" value="primary" />
          <input name="action" type="hidden" value="new_appointment" />
 		  <input name="_token" type="hidden" value="{!! csrf_token() !!}" />
          <input name="new_appointment_txt" type="text" class="form-control" id="new_appointment_text" placeholder="Add a new appointment">
          <button type="submit" class="btn" id="new_appointment_submit">Submit</button>
          </div>
        </form>
        <div id="calendar_stage">
			@php
				$spinner = \App\Ana::loading_spinner();
				echo '<center>'.$spinner.'</center>';
			@endphp
		</div>
        <script type="text/javascript">
			$(function(){

				$("#new_appointment_form").submit(function(event){
					event.preventDefault();
					var fd = $(this).serialize();
					var url = '/gcal';
					$.ajax({
							type: 'POST',
							mimeType: 'multipart/form-data',
							url: url,
							data: fd
					})
					.done(function(html){
							$.get( "/gcal")
								.done(function( data ) {
									$("#calendar_stage").html(data);
								});
							$("#new_appointment_form").trigger("reset");
					});
				});// end task submit

				 $.get( "/gcal")
					  .done(function( data ) {
					  $("#calendar_stage").html(data);
				  });

			});
        </script>

</div>