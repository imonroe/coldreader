@php
  $aspect_id = $aspect->id;
  $folded = ($aspect->folded) ? 'true' : 'false';
@endphp

<div class="aspect_display col-xs-12 col-sm-6 col-md-6 col-lg-4" id="aspect_id-{!! $aspect->id !!}">
	<div class="panel panel-default" >
			<!-- style="min-width:256px; max-width:640px" -->
		<div class="panel-header" style="display:flex; align-items:center; align-content:flex-end; flex-wrap: nowrap ">

			<div class="aspect_title" style="flex-grow: 10;">
				<h4 style="margin-left:1em;">
					
					@empty($aspect->title)
						&nbsp;
					@endempty
					
					{{{ $aspect->title }}}
					
				</h4>
			</div>

			<div class="panel-controls" style="flex: 0 0 3em; padding-left: .5em;">
				<aspect-panel-controls aspect-id="{!! $aspect_id !!}" folded='{!! $folded !!}'></aspect-panel-controls>
			</div>
			
		</div>

		<div class="panel-body">
			@if ( $aspect->folded )
			<div id="collapsable-{!! $aspect->id !!}" style="display:none;">
			@else
			<div id="collapsable-{!! $aspect->id !!}" >
			@endif

			{!!  $aspect->display_aspect()  !!}

      @if ( $aspect->can_edit() )
      <aspect-control-drawer aspect-id="{!! $aspect_id !!}" created-at="{{ $aspect->created_at }}"></aspect-control-drawer>
      @endif

		</div> <!-- end collapsable -->

		</div> <!-- end panel body -->
	</div> <!-- end panel -->
</div>
