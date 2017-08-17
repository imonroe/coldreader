@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> Search Results </div>
                <div class="panel-body">

					<!-- Subject results, if available -->

					@if( count($subject_search_results) > 0 )
					<h2>Coldreader Results</h2>
					<h3 style="clear:both;"> Subjects </h3>
						<ul>
						@foreach ($subject_search_results as $subject)
							<li> <a href="/subject/{!! $subject->id !!}"> {!! $subject->name !!} </a> </li>
						@endforeach
						</ul>
					@endif

					<!-- Google Drive results, if available.  -->
					@if ( count($google_drive_results) > 0 )
					<h3>Google Drive Results</h3>
						<ul>
						@foreach ($google_drive_results as $link => $title)
							<li><a href="{!! $link !!}"> {!! $title !!} </a></li>
						@endforeach
						</ul>
					@endif



					<!-- Abstract, if available -->

					@if ($abstract['AbstractText'])
					<h4> Abstract: {!!  $abstract['Heading'] or '' !!} </h4>
						@if ($abstract['Image'])
							<div class="abstract_img" style="width:300px; float:left; margin:10px;"> 
								<a href="{!! $abstract['Image']  !!}"> 
									<img src="{!! $abstract['Image'] !!}" style="width:100%;">
								</a>
							</div>
						@endif
						<p>{!!  $abstract['AbstractText'] !!}</p>
						<p><a href="{!!  $abstract['AbstractURL'] !!}" class="btn btn-default"> More ... </a></p>
						@if( count($subject_search_results) < 1 )
							<p> <a href="/subject/create_from_search/{!! $abstract['Heading'] !!}" class="btn btn-default"> Create a new Subject from this ... </a></p>
						@endif
					@endif

					<!-- Web Results from DDG  -->
					@if ( !empty( $abstract['Results'] ) )
					<h4 style="clear:both;"> Results from DuckDuckGo </h4>	
						<ul>
						@foreach ($abstract['Results'] as $r)
							<li> {!! $r['Result'] !!}</li>
						@endforeach 
						</ul>
					@endif

					@php  //dd($google_search_results);  @endphp
					<!-- Google Search results  -->
					@if ( !empty($google_search_results) )

					<h4 style="clear:both;"> Results from Google </h4>	
						<ul>
						@foreach ($google_search_results['items'] as $r)
							<li>
								<a href="{!! $r['link'] !!}">{!! $r['htmlTitle'] !!}</a>
								@if ( !empty($r['htmlSnippet']) )
									<p>{!! $r['htmlSnippet'] !!}</p>
								@endif
							</li>
						@endforeach 
						</ul>
					@endif

					<!-- Related Topics, if available -->
					@if ( !empty($abstract['RelatedTopics']) )
					<h5 style="clear:both;"> Related Topics </h5>
						<ul>
						@foreach ($abstract['RelatedTopics'] as $topic)
							@if ( !empty( $topic['Result'] ) )
							<li style="clear:both;">
								@if ( !empty( $topic['Icon']['URL'] ) )
									<div style="width:100px; float:left; margin:5px;">
										<a href="{!! $topic['Icon']['URL'] !!}">
											<img src="{!! $topic['Icon']['URL'] !!}" style="width:100%;">
										</a>
									</div>
								@endif
								{!! $topic['Result'] !!}
							</li>
							@endif
						@endforeach
						</ul>	
					@endif

					<hr style="clear:both;">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection