@extends('layouts.app')

@section('content')

<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
  <div class="panel-heading">  {{ $title or ''}} </div>

  <div class="panel-body">
  	@if ($errors->any())
  	<div class="alert alert-danger">
  		<ul>
  			@foreach ($errors->all() as $error)
  			<li>{{ $error }}</li>
  			@endforeach
  		</ul>
  	</div>
  	@endif
  	<!-- place content here. -->
  	<!-- Form reference: https://laravelcollective.com/docs/5.5/html -->

  		{!! $form !!}


  </div>
</div>
@endsection
