@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">  System Log </div>
                <div class="panel-body">

					<!-- place content here. --> 
					@foreach ($log_items as $item)

					@endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection