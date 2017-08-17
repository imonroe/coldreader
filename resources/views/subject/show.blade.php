@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> 
					<h4> {{ $subject->name }} </h4>
					<span style="float:right;margin-top:-35px;"> <a href="/subject/{{ $subject->id }}/edit" class="btn btn-default">Edit</a> | <a href="/subject/{{ $subject->id }}/delete" class="btn btn-default confirmation" >Delete</a> </span>
				</div>
                <div class="panel-body"> 
					<p>Subject Type: <a href="/subject_type/{{ $subject->subject_type()->id }}">{{ $subject->subject_type()->type_name }}</a></p>

					<p>{!! $subject->get_jump_menu() !!}</p>
					<hr />

					<div class="sortable">
					@foreach ($subject->sorted_aspects() as $aspect)
						@include('aspect.aspect_field', array('aspect'=>$aspect))
					@endforeach
					</div>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(function(){
		$(".reorder-handle").show();
		$(".sortable").sortable({
			axis: 'y',
			handle: '.reorder-handle',
			update: function (event, ui) {
				var data = $(this).sortable('serialize');
				data = data + '&_token={{ csrf_token() }}'
				$.post( "/subject/aspect_sorter", data );
			}
		});
	});	
</script>
@endsection