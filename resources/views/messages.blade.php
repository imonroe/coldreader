@if(Session::has('message'))
  <system-notifications notification-type="info" message="{{ Session::get('message') }}" ></system-notifications>
@endif

@if(Session::has('error'))
  <system-notifications notification-type="error" message="{{ Session::get('error') }}" ></system-notifications>
@endif
