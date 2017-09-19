@extends('layouts.app')

@section('content')
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
	<!-- Form reference: https://laravelcollective.com/docs/5.4/html -->
	<span class="form-horizontal">
		{!! $form !!}
	</span>

</div>

<script type="text/javascript">
</script>
@endsection