@extends('emails.email_layout')

@section('content')
  <h3>Hi!</h3>

  <p>You have been given an invitation to join Coldreader.</p>

  <p>Coldreader is a new kind of personal content management system.  With Coldreader, you can organize your thoughts, keep notes, record research, and more.</p>

  <p>To create your account, just head on over to:</p>

  <p>{{ env('APP_URL') }}/register</p>

  <p>Your invitation code is:</p>

  <p>{!! $invite->code !!}</p>

  <p>Your invitation code is only valid for this email address: {{ $invite->for }}</p>

  <p>Your invitation code is valid until {{ $invite->valid_until }}</p>

  <p>If you have any questions, or if you have problems creating your account, please email ian@ianmonroe.com for assistance.</p>

  <p>Thanks!  We hope you enjoy Coldreader!</p>
@endsection
