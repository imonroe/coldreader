@extends('layouts.app')




@section('content')
<div class="panel panel-default" style="margin-left:.6em; margin-right:.6em;">
  
  <div class="panel-header" style="padding:.6em;">
    <h4> Codex </h4>

    @if ($type_id > 0 && false)
  	  <span style="float:right; margin-top:-35px;"> 
        <a href="/subject_type/{{ $type_id }}/edit" class="btn btn-default">Edit</a> | 
        <a href="/subject_type/{{ $type_id }}/delete" class="btn btn-default confirmation">Delete</a> 
      </span>
    @endif

  </div>

  <div class="panel-body">
    
    <p>{{ $type_description }}</p>
    
    @if ($type_id > 0)
      <coldreader-codex currently-selected="{{ $type_id }}"></coldreader-codex>
    @else
      <coldreader-codex></coldreader-codex>
    @endif

    

  </div>

</div>
@endsection
