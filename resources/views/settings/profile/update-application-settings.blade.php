@php
    $prefs_controller = new imonroe\crps\Http\Controllers\UserPreferencesController;
    $prefs_form = $prefs_controller->get_preference_form();
@endphp

@extends('layouts.app')

@section('content')

    
        <div class="panel panel-default" style="margin-left:.6em; margin-right:.6em;">
            <div class="panel-heading">Preferences</div>
    
            <div class="panel-body">
    
                {!! $prefs_form !!}
    
                </form>
            </div>
        </div>
    

@endsection