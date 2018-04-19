@extends('spark::layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">ERROR!</div>

                <div class="panel-body">
                    <h1> 403 </h1>
					<p> You are not sufficiently authorized for this request. Your attempt from {{ Request::ip() }} has been logged. </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
