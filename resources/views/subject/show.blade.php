@extends('layouts.app')

@section('content')
<div class="panel-heading"> 
	<h4> {{ $subject->name }} </h4>
	<span style="float:right;margin-top:-35px;"> <a href="/subject/{{ $subject->id }}/edit" class="btn btn-default">Edit</a> | <a href="/subject/{{ $subject->id }}/delete" class="btn btn-default confirmation" >Delete</a> </span>
</div>

<div class="panel-body">

	<div style="float:left; margin-bottom: 1.5em; margin-right:1.5em;">Subject Type: <a href="/subject_type/{{ $subject->subject_type()->id }}">{{ $subject->subject_type()->type_name }}</a></div>

	<div style="float:left; margin-bottom: 1.5em; margin-right:1.5em;">{!! $subject->get_jump_menu() !!}</div>
	<hr style="clear:both;" />

	<div id="grid" class="row d-flex sortable">
		@foreach ($subject->sorted_aspects() as $aspect)
			@include('aspect.aspect_field', array('aspect'=>$aspect))
		@endforeach
	</div>

</div>

<script type="text/javascript">
$(function(){
	$(".reorder-handle").show();
	$(".sortable").sortable({
		handle: '.reorder-handle',
		placeholder: "ui-state-highlight",	
		update: function (event, ui) {
			var data = $(this).sortable('serialize');
			data = data + '&_token=' + Laravel.csrfToken;
			$.post( "/subject/aspect_sorter", data );
			$.rejigger();
		}
	});
});	
</script>
@endsection