@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">  {{ $title }} </div>
                <div class="panel-body">
					<p><a href="/subject/create/" class="btn btn-default">Create a new Subject</a> </p>
					<!-- place content here. --> 

					{!!  $directory  !!}


				</div>
            </div>
        </div>
    </div>
</div>
@endsection