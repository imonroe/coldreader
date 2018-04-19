@extends('layouts.app')

@php

@endphp

@section('content')
<div class="col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1">
  <h1>Contact</h1>

  <p>Got questions?  Send us a message with this form.</p>

  {!! BootForm::horizontal(['url' => '/contact', 'method' => 'post', 'files' => true]) !!}
  {!! BootForm::text('name', 'Your Name'); !!}
  {!! BootForm::email(); !!}
  {!! BootForm::textarea('message', 'Message'); !!}
  {!! BootForm::submit('Send') !!}
  {!! BootForm::close() !!}

</div>
@endsection

@section('title')
Contact us
@endsection
