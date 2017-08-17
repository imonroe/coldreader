@extends('layouts.app')

@section('content')

@php
	$user_data = session('user_data');
@endphp

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> <p>Welcome back, {{ $user_data['name'] }}.  Let's get to work. </p></div>

                <div class="panel-body">

				@foreach ($homepage_aspects->sorted_aspects() as $a)
					@include('aspect.aspect_field', array('aspect'=>$a))
				@endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
