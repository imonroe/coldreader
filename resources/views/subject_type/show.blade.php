@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

			<p><a href="/subject_type" class="btn btn-default">View All Subject Types</a></p>

            <div class="panel panel-default">
                <div class="panel-heading">  <h4> {{ $type->type_name }} </h4>
				<span style="float:right; margin-top:-35px;"> <a href="/subject_type/{{ $type->id }}/edit" class="btn btn-default">Edit</a> | <a href="/subject_type/{{ $type->id }}/delete" class="btn btn-default confirmation">Delete</a> </span>
				</div>
                <div class="panel-body">

					<p>{{ $type->type_description }}</p>

					<div class="small">  
						@if ( $parent_type_id  )
						<p> << Back to <a href="/subject_type/{!! $parent_type_id !!}">{!! $parent_name !!}</a></p>
						@endif
						@if ( $children )
						<p> 
							Drill down:  
							<ul>
								@foreach ($children as $child)
									<li> <a href="/subject_type/{{ $child->id }}">{{ $child->type_name }} </a></li>
								@endforeach
							</ul>
						</p>
						@endif
					</div>

					<p> <a href="/subject/create/{!! $type->id !!}" class="btn btn-default">Add a new {!! $type->type_name !!} Subject</a> </p>

					<h4> Subjects: </h4>
					{{ $subjects->links() }}
					<ul>
					@foreach ($subjects as $s)
						<li><a href="/subject/{{ $s->id }}" >{!! $s->name !!}</a></li>	
					@endforeach
					</ul>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection