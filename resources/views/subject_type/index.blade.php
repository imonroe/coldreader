@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">   {{ $title }}  </div>
                <div class="panel-body">
					<p><a href="/subject_type/create/" class="btn btn-default">Create a new Subject Type</a> </p>
					<!-- place content here. --> 
					
					<div id="tree">
					{!!  $directory  !!}
					</div>
					<!--
					<script type="text/javascript">
						$(function(){
							$("#tree").fancytree({
								checkbox: true,
								selectMode: 1,
								activate: function(event, data){
									var node = data.node;
									console.log("activate: event=", event, ", data=", data);
									if(!$.isEmptyObject(node.data)){
										alert("custom node data: " + JSON.stringify(node.data));
									}
								}
							});
						});
					</script>
					-->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection