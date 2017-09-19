@extends('layouts.app')

@section('content')

@php
	$user_data = session('user_data');
@endphp



<div class="panel-heading"> <p>Welcome back, {{ $user_data['name'] }}.  Let's get to work. </p></div>

<div class="panel-body">

	<div style="width:100%; clear:both;">
	<center><h3> Daily Notes </h3></center>

	<p>{!! $daily_notes->get_jump_menu() !!}</p>
	<hr />	
	</div>

	<div id="grid" class="row d-flex">

	@foreach ($homepage_aspects->sorted_aspects() as $a)
	@include('aspect.aspect_field', array('aspect'=>$a))
	@endforeach


	@foreach ($daily_notes->sorted_aspects() as $aspect)
	@include('aspect.aspect_field', array('aspect'=>$aspect))
	@endforeach

	</div>

</div>

@endsection
