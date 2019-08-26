@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">ERROR!</div>

                <div class="panel-body">
                    <h1> Whoops! </h1>
					<p>Something has gone terribly wrong. Sorry.</p>

          <p>We don't have any other information to give you, but the error has been reported, and we'll try to get it fixed as soon as possible.  Our bad.</p>

          <p>{{ $message }}</p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
