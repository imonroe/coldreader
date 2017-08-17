@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">  {{{ $title }}} </div>
                <div class="panel-body">

					<!-- place content here. --> 
					<a href="/aspect_type/create/" class="btn btn-default">Create a new Aspect Type</a>
					<ul>
					@foreach ($types as $type)
						<li> <a href="/aspect_type/{!! $type->id !!}/edit">{!! $type->aspect_name !!} </a></li>
					@endforeach
					</ul>	
                </div>
            </div>
        </div>
    </div>
</div>
@endsection