@php
	if ($list_id == '@default'){
		$function_id = '';
	} else {
		$function_id = $list_id;
	}
@endphp

<div class="widget" id="todo_list_{!! $css_id !!}">
	<h3>{!! $list_title !!}</h3>
        <form class="form-inline" id="new_task_form_{!! $css_id !!}">
          <input name="due" type="hidden" value="" />
		  <input name="_token" type="hidden" value="{!! csrf_token() !!}" >
          <input name="task_list" type="hidden" value="{!! $list_id !!}" >
          <input name="action" type="hidden" value="new_todo_item" >
          <input name="new_task_title" type="text" class="form-control" id="new_task_title" placeholder="Add a new task">
          <button type="submit" class="btn" id="new_task_submit">Submit</button>
        </form>

    	<div id="todo_stage_{!! $css_id !!}" style="">
		@php
			$spinner = \App\Ana::loading_spinner();
			echo '<center>'.$spinner.'</center>';
		@endphp
		</div>
        <script type="text/javascript">
			$(function(){

				$("#new_task_form_{!! $css_id !!}").submit(function(event){
					event.preventDefault();
					var fd = $(this).serialize();
					console.log(fd);
					var url = '/gtasks';
					$.ajax({
							type: 'POST',
							mimeType: 'multipart/form-data',
							url: url,
							data: fd
					})
					.done(function(html){
							$.get( "/gtasks/{!! $list_id !!}")
								.done(function( data ) {
									$("#todo_stage_{!! $css_id !!}").html(data);
								});
							$("#new_task_form_{!! $css_id !!}").trigger("reset");
					});
				});// end task submit

				$.get( "/gtasks/{!! $list_id !!}")
            			.done(function( data ) {
            			$("#todo_stage_{!! $css_id !!}").html(data);
        		});

			});

			function closeTodoItem_{!! $function_id !!}(item){
				var item_id = item.getAttribute('id');
				$.post( "/gtasks", 
						{ action: "complete_todo_item", task_id: item_id, _token: '{!! csrf_token() !!}', list_id:'{!! $list_id !!}'})
            			.done(function( data ) {
            				$.get( "/gtasks/{!! $list_id !!}")
							.done(function( data ) {
							$("#todo_stage_{!! $css_id !!}").html(data);
						});
        		});

			}
        </script>

</div>