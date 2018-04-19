@extends('spark::layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Today's News</div>
                <div class="panel-body">

					<!-- place content here. -->

					@foreach ($news_aspects->sorted_aspects() as $a)
						@include('aspect.aspect_field', array('aspect'=>$a))
					@endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
