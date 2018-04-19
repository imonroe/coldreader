<div class="widget news_feed" >

<h4><a href="{!! $feed_output->id !!}">{!! $feed_output->title !!}</a></h4>

    @php
        $feed_array = (array)$feed_output->getItems();
		$counter = 0;
    @endphp

<ul>
    @foreach($feed_array as $feed_item)  
		@if ( $counter < $number_of_items)
		<li>
        <a href="{!! $feed_item->url !!}" target="_blank">{!! $feed_item->title !!}</a><br />
        <span class="small">@php echo date('l jS \of F Y h:i:s A', $feed_item->date->getTimestamp() ); @endphp </span>
    	</li>
		@endif
		@php $counter++; @endphp
    @endforeach

</ul>



</div>