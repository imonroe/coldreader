@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">  {{ $title or ''}} </div>
                <div class="panel-body">

					<!-- place content here. --> 
					<!-- Form reference: https://laravelcollective.com/docs/5.4/html -->
					{!! $form !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection