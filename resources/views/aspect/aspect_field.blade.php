<div class="aspect_display col col-xs-12 col-sm-12 col-md-6 col-xl-4" style="min-width:256px;"  id="aspect_id-{!! $aspect->id !!}">
	<div class="panel panel-default">
			<!-- style="min-width:256px; max-width:640px" -->
		<div class="panel-header">
			<div class="panel-controls" style="float:right; margin:-.3em 1em 1em 1em; clear:none; display:block;"> 
				<span id="reorder-{!! $aspect->id !!}" style="margin:1em; display:none;" class="reorder-handle"> 
					<i class="fa fa-arrows" aria-hidden="true" ></i>
				</span>

				@if ( $aspect->folded )
				<span id="roll-up-{!! $aspect->id !!}" style="margin:1em; display:none;"> 
					<i class="fa fa-caret-down" aria-hidden="true"></i>
				</span>
				<span id="roll-down-{!! $aspect->id !!}" style="margin:1em;"> 
					<i class="fa fa-caret-left" aria-hidden="true"></i>
				</span>
				@else
				<span id="roll-up-{!! $aspect->id !!}" style="margin:1em;"> 
					<i class="fa fa-caret-down" aria-hidden="true"></i>
				</span>
				<span id="roll-down-{!! $aspect->id !!}" style="margin:1em; display:none;"> 
					<i class="fa fa-caret-left" aria-hidden="true"></i>
				</span>
				@endif
			</div>

			@if ( !empty($aspect->title) )
			<h4 style="margin-left:1em;"> {{{ $aspect->title }}}</h4>
			@else
			<h4>&nbsp;</h4>
			@endif
		</div>

		<div class="panel-body" style="word-wrap: break-word;">
			@if ( $aspect->folded )
			<div id="collapsable-{!! $aspect->id !!}" style="display:none;">
			@else 
			<div id="collapsable-{!! $aspect->id !!}" >	
			@endif

			{!!  $aspect->display_aspect()  !!}

			<div id="aspect_control_drawer_{!! $aspect->id !!}">
				<div id="aspect_settings_toggle_{!! $aspect->id !!}" class="aspect_meta" style="width:25px; padding:5px;float:left;">
					<i class="fa fa-cogs" aria-hidden="true"></i>
				</div>

				<div id="aspect_controls_{!! $aspect->id !!}" style="display:none;"> 
					<a href="#" onclick="aspect_{!! $aspect->id !!}_edit_button(); return false;" id="{!! $aspect->id !!}-edit-button" class="btn btn-primary">Edit</a> <a href="#" onclick="aspect_{!! $aspect->id !!}_delete_button(); return false;" id="{!! $aspect->id !!}-delete-button" class="btn btn-danger" >Delete</a>  Added: {{ $aspect->created_at }}
				</div>
			</div>

		</div> <!-- end collapsable -->

		</div> <!-- end panel body -->
	</div> <!-- end panel -->

	<script type="text/javascript">
		function aspect_{!! $aspect->id !!}_edit_button(){
			window.location.href = '/aspect/{!! $aspect->id !!}/edit';
			return false;
		}

		function aspect_{!! $aspect->id !!}_delete_button(){
			if (confirm('Are you sure you want to do that?')){
				window.location.href = '/aspect/{!! $aspect->id !!}/delete';
			}
			return false;
		}

		$(function(){
			$( "#aspect_settings_toggle_{!! $aspect->id !!}" ).click(function(){
				//$( this).width('80%');
				$("#aspect_controls_{!! $aspect->id !!}").show();
				return false;
			});

			$( "#aspect_control_drawer_{!! $aspect->id !!}" ).mouseleave(function(){
				$("#aspect_controls_{!! $aspect->id !!}").hide();
				//$( this ).html('<i class="fa fa-cogs" aria-hidden="true"></i>');
				//$( this ).width('20px');
			});

			$("#roll-up-{!! $aspect->id !!}").click(function(){
				$("#collapsable-{!! $aspect->id !!}").toggle("slow", function(){
					$("#roll-up-{!! $aspect->id !!}").hide();
					$("#roll-down-{!! $aspect->id !!}").show();
					$.rejigger();
				});
				$.post( "/aspect/{!! $aspect->id !!}/fold", { _token: "{{ csrf_token() }}"} );

			});

			$("#roll-down-{!! $aspect->id !!}").click(function(){
				$("#collapsable-{!! $aspect->id !!}").toggle("slow", function(){
					$("#roll-down-{!! $aspect->id !!}").hide();
					$("#roll-up-{!! $aspect->id !!}").show();
					$.rejigger();
				});
				$.post( "/aspect/{!! $aspect->id !!}/fold", { _token: "{{ csrf_token() }}"} );

			});
		});
	</script>
</div>