@extends('layouts.app')

@section('content')
<div class="panel-heading">  {{ $title }} </div>
<div class="panel-body">
	<p><a href="/subject/create/" class="btn btn-default">Create a new Subject</a> </p>

  

	<subject-navigator :menu="{{ json_encode($codex_array) }}"></subject-navigator>


</div>
@endsection
