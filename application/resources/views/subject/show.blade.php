@extends('layouts.app')

@php
  $jump_menu_array = $subject->get_jump_menu_json();
  $subject_type = $subject->subject_type();

  $all_aspects = $subject->sorted_aspects();
  
  //dd($all_aspects);
  //dd($jump_menu_array);
  //dd($codex);
  //dd($description);
@endphp

@section('content')
<div class="panel panel-default" style="margin-left:.6em; margin-right:.6em;">
  <div class="panel-header" style="padding:.6em;">

      @if ($subject->editable)
      <div class="pull-right" style="clear:none;">
        <a href="/subject/{{ $subject->id }}/edit" class="btn btn-default">Edit</a> |
        <a href="/subject/{{ $subject->id }}/delete" class="btn btn-default confirmation" >Delete</a>
      </div>
      @endif

      <h4> {{ $subject->name }} </h4>
      {!! $description !!}

      @if ($subject_type)
        Subject Type: <a href="/subject_type/{{ $subject_type->id }}">{{ $subject_type->type_name }}</a>
      @endif

  </div>

  <div class="panel-body">
    	<div style="float:left; margin-bottom: 1.5em; margin-right:1.5em;">

        <add-aspect-jump-menu
          :option-list="{{ $jump_menu_array }}"
          :subject-id="{{ $subject->id }}"
        >
        </add-aspect-jump-menu>

        @if (!empty($parent['parent_name']))
          <!-- Filed under:<a href="/subject/{{ $parent['parent_id'] }}" class="btn btn-default">{{ $parent['parent_name'] }}</a> -->
        @endif

        @if (!empty($codex))
          <!-- <subject-child-cascade-jump :menu="{{ json_encode($codex) }}"></subject-child-cascade-jump> -->
        @endif

        <!-- <a href="/subject/create/{{ $subject->id }}" class="btn btn-default">Add a child subject</a> -->
      </div>

    	<hr style="clear:both;" />

    	<div id="grid" class="row sortable">
        @if ($all_aspects->isEmpty())
          <div style="text-align: center;"><h3>Nothing here yet.</h3></div>
        @endif

    		@foreach ($all_aspects as $aspect)
    			@include('aspect.aspect_field', array('aspect'=>$aspect))
    		@endforeach
      
      </div>

  </div>
</div>
@endsection
