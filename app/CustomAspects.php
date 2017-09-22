<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use \Goutte\Client;
use imonroe\crps\Aspect;
use imonroe\crps\AspectType;
use imonroe\crps\AspectFactory;
use imonroe\crps\Subject;
use imonroe\crps\SubjectType;
use imonroe\ana\Ana;
use \WolframAlpha;

// ---------  Begin Custom Aspects -------------- //

/*
Hey, you're going to see a line at the end of this file that looks like:

// ------------------------------------------- //

DO NOT REMOVE OR MODIFY THAT LINE.  IT IS THE FIND AND REPLACE TOKEN THAT ADDS NEW CUSTOM ASPECT CODE.
IF YOU REMOVE OR CHANGE IT, YOU WILL BREAK THE APP.
*/

class WebpageAspect extends Aspect{
	public function create_form($subject_id, $aspect_type_id=null){
		$form = \Form::open(['url' => '/aspect/create', 'method' => 'post', 'files' => true]);
		$form .= \Form::hidden('subject_id', $subject_id);
		$form .= \Form::hidden('aspect_type', $aspect_type_id );
		$form .= \Form::hidden('aspect_data');
		$form .= \Form::hidden('hidden', '0');
		$form .= \Form::hidden('file_upload');
		$form .= '<p>';
		$form .= \Form::label('title', 'Title: ');
		$form .= \Form::text('title');
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('aspect_source', 'URL: ');
		$form .= \Form::text('aspect_source');
		$form .= '</p>';
		$form .= $this->notes_fields();
		$form .= '<p>' .\Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
        return $form;
	}
	public function edit_form($id){
		$current_aspect = Aspect::find($id);
		$form = \Form::open(['url' => '/aspect/'.$id.'/edit', 'method' => 'post', 'files' => false]);
		$form .= \Form::hidden('subject_id', $current_aspect->subjects()->first()->id);
		$form .= \Form::hidden('aspect_type', $current_aspect->aspect_type()->id );
		$form .= \Form::hidden('aspect_data', $current_aspect->aspect_data);
		$form .= \Form::hidden('hidden', $current_aspect->hidden);
		$form .= \Form::hidden('file_upload');
		$form .= '<p>';
		$form .= \Form::label('title', 'Title: ');
		$form .= \Form::text('title', $current_aspect->title);
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('aspect_source', 'URL: ');
		$form .= \Form::text('aspect_source', $current_aspect->aspect_source);
		$form .= '</p>';
		$form .= $this->notes_fields();
		$form .= '<p>' . \Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
        return $form;
	}
	public function display_aspect(){
		$output = '<div class="aspect_type-'.$this->aspect_type()->id.'">';
		//$output .= '<h4>'.$this->title.'</h4>';
		$output .= '<p><strong>URL: </strong>';
		$output .= '<a href="'.$this->aspect_source.'" target="_blank">'.$this->aspect_source.'</a>';
		$output .= '</p></div>';
		return $output;
	}
	public function parse(){}
}  // End of the WebpageAspectclass.

// default custom class created automatically.

class RSSFeedAspect extends WebpageAspect{

	protected $feed;

	function __construct(){
		parent::__construct();
		$this->keep_history = false;

	}

	public function notes_schema(){
		$settings = json_decode(parent::notes_schema(), true);
		$settings['number_of_items'] = '5';
		return json_encode($settings);
	}

	public function create_form($subject_id, $aspect_type_id=null){
		return parent::create_form($subject_id, $this->aspect_type);
	}
	public function edit_form($id){
		return parent::edit_form($id);
	}
	public function display_aspect(){
		$settings = (!is_null($this->aspect_notes)) ? json_decode($this->aspect_notes, true) : json_decode($this->notes_schema(), true);

		$news_agent = new \App\Http\Controllers\RSSController;
		$feed = $news_agent->get_feed_via_ajax($this->id);
		$this->title = (!empty($feed->title)) ? $feed->title : 'error' ;   // <h4><a href="{$feed->id}">{$feed->title}</a></h4>

			if (! is_string($feed) ){
				$feed_array = (array) $feed->getItems();
			} else {
				return 'There was an error fetching the feed.';
			}

		$counter = 0;

		$max_items = ( !empty( $settings['number_of_items'] ) ) ? (int) $settings['number_of_items'] : 5;

		$feed_logo = (!empty($feed->logo)) ? $feed->logo : false;
		$feed_content = '<h4><a href="'.$feed->id.'">'.$feed->title.'</a></h4>';
		if ($feed_logo){
			// it'd be nice if this works, but the output is unpredictable.
			//$feed_content .= '<img src="'.$feed_logo.'" style="float:left; margin:10px;">'.PHP_EOL;
		}
		$feed_content .= '<ul>'.PHP_EOL;
		foreach($feed_array as $feed_item){
			if ( $counter < $max_items){
				$feed_content .= '<li>'.PHP_EOL;
				$feed_content .= '<a href="'.$feed_item->url.'" target="_blank">'.$feed_item->title.'</a><br/>'.PHP_EOL;
				$feed_content .= '<span class="small">'.date('l jS \of F Y h:i:s A', $feed_item->date->getTimestamp() ).'</span>'.PHP_EOL;
				$feed_content .= '</li>'.PHP_EOL;
				$counter++;
			}
		}
		$feed_content .= '</ul>'.PHP_EOL;
		$output = <<<OUTPUT_STRING
<div class="widget news_feed" >
	$feed_content
</div>		
OUTPUT_STRING;
		return $output;
	}

	public function parse(){
	    //Log::info('entered parse function for Aspect: '.$this->id);
		//if (empty($this->last_parsed) || strtotime($this->last_parsed) < strtotime('now -1 hour') ){
			// do something?
		//}
		//$this->last_parsed = Carbon::now();
		//$this->update_aspect();
	}
}  // End of the RSSFeedAspectclass.

// default custom class created automatically.

class LocationAspect extends Aspect{
	public function notes_schema(){
		$settings = parent::notes_schema();
		$settings['formatted_address'] = '';
		$settings['street_address'] = '';
		$settings['city'] = '';
		$settings['state'] = '';
		$settings['zip'] = '';
		return json_encode($settings);
	}
	public function create_form($subject_id, $aspect_type_id=null){
		$output = $this->display_aspect() . '<hr />';
		$form = \Form::open(['url' => '/aspect/create', 'method' => 'post', 'files' => true]); 
		$form .= \Form::hidden('subject_id', $subject_id);
		$form .= \Form::hidden('aspect_type', $aspect_type_id );
		$form .= \Form::hidden('aspect_source', null);
		$form .= '<p>'.\Form::label('title', 'Title: '). \Form::text('title', '') .'</p>;';
		$form .= \Form::hidden('aspect_data');
		$form .= \Form::hidden('hidden', '1');
		$form .= \Form::hidden('file_upload');
		$form .= $this->notes_fields();
		$form .= '<p>' .\Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
		$output .= $form;
        return $output;
	}
	public function edit_form($id){
		$output = $this->display_aspect() . '<hr />';
		$form = \Form::open(['url' => '/aspect/'.$this->id.'/edit', 'method' => 'post', 'files' => false]); 
		$form .= \Form::hidden('subject_id', $this->subject_id);
		$form .= \Form::hidden('aspect_type', $this->aspect_type );
		$form .= \Form::hidden('aspect_source',$this->aspect_source);
		$form .= '<p>'.\Form::label('title', 'Title: '). \Form::text('title', $this->title) .'</p>;';
		$form .= \Form::hidden('aspect_data', $this->aspect_data);
		$form .= \Form::hidden('hidden', $this->hidden);
		$form .= \Form::hidden('file_upload');
		$form .= $this->notes_fields();
		$form .= '<p>' .\Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
		$output .= $form;
        return $output;
	}
	public function display_aspect(){
		$settings = (!is_null($this->aspect_notes)) ? json_decode($this->aspect_notes, true) : json_decode($this->notes_schema(), true);
		$formatted_address = '';
		if (!empty($settings['formatted_address'])){
			$formatted_address .= $settings['formatted_address']; 
		} else {
			if (!empty($settings['street_address'])){
				$formatted_address .= $settings['street_address'] . ' ';
			}
			if (!empty($settings['city'])){
				$formatted_address .= $settings['city'] . ', ';
			}
			if (!empty($settings['state'])){
				$formatted_address .= $settings['state'] . ' ';
			}
			if (!empty($settings['zip'])){
				$formatted_address .= $settings['zip'] . ' ';
			}
		}
		$output = parent::display_aspect();
		//$output .= '<h4>'.$this->title.'</h4>';
		$output .= $formatted_address . '<br />' . PHP_EOL;
		$output .= \imonroe\cr_aspects_google\Http\Controllers\GoogleController::get_static_map($formatted_address);
		$output .= '<div style="clear:both;"></div>';
		return $output;
	}
	public function parse(){}
}  // End of the LocationAspectclass.

// default custom class created automatically.

class EventAspect extends Aspect{
	public function notes_schema(){
		return parent::notes_schema();
	}
	public function create_form($subject_id, $aspect_type_id=null){
		return parent::create_form($subject_id, $this->aspect_type);
	}
	public function edit_form($id){
		return parent::edit_form($id);
	}
	public function display_aspect(){
		$output = parent::display_aspect();
		return $output;
	}
	public function parse(){}
}  // End of the EventAspectclass.

class WeatherReportAspect extends LamdaFunctionAspect{
	public function notes_schema(){
		$settings = parent::notes_schema();
		$settings['zip_code'] = '';
		return json_encode($settings);
	}
	public function create_form($subject_id, $aspect_type_id=null){
		return parent::create_form($subject_id, $this->aspect_type);
	}
	public function edit_form($id){
		return parent::edit_form($id);
	}
	public function display_aspect(){
		$output = parent::display_aspect();
		return $output;
	}
	public function parse(){}

	public function lambda_function(){
		$options = (!is_null($this->aspect_notes)) ? json_decode($this->aspect_notes, true) : json_decode($this->notes_schema(), true);
		//eturn var_export($options, true);
		$zip = (!empty($options['zip_code'])) ? $options['zip_code'] : '60606';
		$weather_data = Ana::get_weather_data_by_zip($zip);
		$moon_phase = Ana::moon_phase();
		$conditions = $weather_data['weather'][0]['description'];
		$temperature = $weather_data['main']['temp'];
		$humidity = $weather_data['main']['humidity'];
		$output = '<img src ="https://openweathermap.org/img/w/'.$weather_data['weather'][0]['icon'].'.png" style="float:left; margin-right:15px;">'.PHP_EOL;
		$output .= '<p>In the '.$zip.' zip code, the weather is currently '.$conditions.'. The temperature now is '.$temperature.' degrees Fahrenheit, and the humidity is '.$humidity.'%.  Today, the moon phase is: '.$moon_phase.'.</p>';
		return $output;
	}

}  // End of the WeatherReportAspectclass.

class SketchAspect extends Aspect{
	public function __construct(){
		parent::__construct();
		$this->keep_history = false; 
	}
	public function notes_schema(){
		return parent::notes_schema();
	}
	public function create_form($subject_id, $aspect_type_id=null){
		// DOCUMENTATION: http://literallycanvas.com/api/initializing.html
		$form = <<<EXTRA_DIV
	<!-- where the widget goes. you can do CSS to it. -->
    <!-- note: as of 0.4.13, you cannot use 'literally' as the class name.
         sorry about that. -->
    <div class="my-drawing" ></div>

	<button class="btn btn-default" id="save-canvas-snapshot">Save Snapshot</button>

    <!-- kick it off -->
    <script>	
		$(function(){
			var lc = LC.init(
            	document.getElementsByClassName('my-drawing')[0],
            	{imageURLPrefix: '/js/literally_canvas/_assets/lc-images'}
        	);

			$("#save-canvas-snapshot").click(function(e){
				var snapshot = JSON.stringify(lc.getSnapshot());
				console.log(snapshot);
			});

			// update the data field when the drawing changes.
			var unsubscribe = lc.on('drawingChange', function() {
  				//localStorage.setItem('drawing', lc.getSnapshotJSON());
				$('[name="aspect_data"]').val( JSON.stringify(lc.getSnapshot()) );
			});

			//unsubscribe();  // stop listening
		});
    </script>
EXTRA_DIV;
		//$form .= parent::create_form($subject_id, $this->aspect_type);
		$form .= $this->display_aspect() . '<hr />';
		$form .= \Form::open(['url' => '/aspect/create', 'method' => 'post', 'files' => true]); 
		$form .= \Form::hidden('subject_id', $subject_id);
		$form .= \Form::hidden('aspect_type', $aspect_type_id );
		$form .= \Form::hidden('title', '');
		$form .= \Form::hidden('aspect_source', null);
		$form .= \Form::hidden('aspect_data');
		$form .= \Form::hidden('hidden', '1');
		$form .= \Form::hidden('file_upload');
		$form .= $this->notes_fields();
		$form .= '<p>' .\Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
		return $form;
	}
	public function edit_form($id){
		$form = <<<EXTRA_DIV
	<!-- where the widget goes. you can do CSS to it. -->
    <!-- note: as of 0.4.13, you cannot use 'literally' as the class name.
         sorry about that. -->
    <div class="my-drawing" ></div>
    <!-- kick it off -->
    <script>	
		$(function(){
			var lc = LC.init(
            	document.getElementsByClassName('my-drawing')[0],
            	{imageURLPrefix: '/js/literally_canvas/_assets/lc-images'}
        	);		
			var snapshotJSON = $('[name="aspect_data"]').val();
			lc.loadSnapshot(JSON.parse(snapshotJSON));
			$("#save-canvas-snapshot").click(function(e){
				var snapshot = JSON.stringify(lc.getSnapshot());
				console.log(snapshot);
			});
			// update the data field when the drawing changes.
			var unsubscribe = lc.on('drawingChange', function() {
  				//localStorage.setItem('drawing', lc.getSnapshotJSON());
				$('[name="aspect_data"]').val( JSON.stringify(lc.getSnapshot()) );
			});
			//unsubscribe();  // stop listening
		});
    </script>
EXTRA_DIV;
		//$form .= $this->display_aspect() . '<hr />';
		$form .= \Form::open(['url' => '/aspect/'.$this->id.'/edit', 'method' => 'post', 'files' => true]); 
		$form .= \Form::hidden('subject_id', $this->subject_id);
		$form .= \Form::hidden('aspect_type', $this->aspect_type );
		$form .= \Form::hidden('title', $this->title);
		$form .= \Form::hidden('aspect_source',$this->aspect_source);
		$form .= \Form::hidden('aspect_data', $this->aspect_data);
		$form .= \Form::hidden('hidden', $this->hidden);
		$form .= \Form::hidden('file_upload');
		$form .= $this->notes_fields();
		$form .= '<p>' .\Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
        return $form;
	}
	public function display_aspect(){
		$output = '<div id="rendered_sketch_'.$this->id.'" style="width:100%"><img id="rendered_img_'.$this->id.'" src="" style="width:100%"></div><div id="empty_'.$this->id.'"></div>';
		$output .= <<<GET_IMAGE
		<script>
		$(function(){
			var snapshotJSON = '$this->aspect_data';
			var snapshot = JSON.parse(snapshotJSON);
			var rendered = LC.renderSnapshotToImage(snapshot, {scale:1});
			var img_src = rendered.toDataURL();
			$("#empty_$this->id").hide();
			$("#rendered_img_$this->id").attr("src",img_src);
		});
		</script>		
GET_IMAGE;
		return $output;
	}
	public function parse(){}
}  // End of the SketchAspectclass.

class WebScraperAspect extends LamdaFunctionAspect{
	public function __construct(){
		parent::__construct();
		$this->keep_history = false; 
	}
	public function notes_schema(){
		$schema = array();
		$schema = json_decode(parent::notes_schema(), true);
		$schema['webpage_url'] = '';
		$schema['DOM_selector'] = '';
		$schema['widget_title'] = '';
		return json_encode($schema);
	}
	public function create_form($subject_id, $aspect_type_id=null){
		$output = $this->display_aspect() . '<hr />';
		$form = \Form::open(['url' => '/aspect/create', 'method' => 'post', 'files' => true]); 
		$form .= \Form::hidden('subject_id', $subject_id);
		$form .= \Form::hidden('aspect_type', $aspect_type_id );

		$form .= '<p>' . \From::label('title', 'Title: ') . \Form::text('title', '') . '</p>';

		$form .= \Form::hidden('aspect_source', null);
		$form .= \Form::hidden('aspect_data');
		$form .= \Form::hidden('hidden', '1');
		$form .= \Form::hidden('file_upload');
		$form .= $this->notes_fields();
		$form .= '<p>' .\Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
		$output .= $form;
        return $output;
	}
	public function edit_form($id){
		$output = $this->display_aspect() . '<hr />';
		$form = \Form::open(['url' => '/aspect/'.$this->id.'/edit', 'method' => 'post', 'files' => false]); 
		$form .= \Form::hidden('subject_id', $this->subject_id);
		$form .= \Form::hidden('aspect_type', $this->aspect_type );
		$form .= '<p>' . \Form::label('title', 'Title: ') . \Form::text('title', $this->title) . '</p>';
		$form .= \Form::hidden('aspect_source',$this->aspect_source);
		$form .= \Form::hidden('aspect_data', $this->aspect_data);
		$form .= \Form::hidden('hidden', $this->hidden);
		$form .= \Form::hidden('file_upload');
		$form .= $this->notes_fields();
		$form .= '<p>' .\Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
		$output .= $form;
        return $output;
	}
	public function display_aspect(){
		$output = parent::display_aspect();
		return $output;
	}
	public function parse(){}

	public function lambda_function(){
		$settings = (!is_null($this->aspect_notes)) ? json_decode($this->aspect_notes, true) : json_decode($this->notes_schema(), true);
		$output = '<h4>'.$settings['widget_title'].'</h4>';
		$output .= '<div style="display:flex; flex-wrap:wrap; clear:both;">';
		$client = new Client();
		$crawler = $client->request('GET', $settings['webpage_url']);

		foreach ($crawler->filter($settings['DOM_selector'])->children() as $domElement){
			$domElement->removeAttribute('style');
			$domElement->removeAttribute('class');
			$domElement->removeAttribute('id');
			$output .= $domElement->ownerDocument->saveHTML($domElement);
			//$output .= $domElement->text();
		}
		$output .= '</div>';
		return $output;
	}

	public function cleanse_element($domElement){
		$output = new DOMElement;
		$domElement->removeAttribute('style');
		$domElement->removeAttribute('class');
		$domElement->removeAttribute('id');
		if ($domElement->hasChildNodes()){
			$nodeList = $domElement->childNodes;
			foreach ($nodeList as $node) {
    			//echo $node->nodeValue;
			}
		}
		$children = '';
		return $output;
	}

}  // End of the WebScraperAspectclass.

class GeolocationAspect extends LocationAspect{
	protected $latitude;
	protected $longitude;
	protected $accuracy;

	public function __construct(){
		parent::__construct();
		$this->keep_history = false;
		$this->title = "Current Location";
		$this->latitude = (!empty($_COOKIE['current_latitude'])) ? $_COOKIE['current_latitude'] : '';
		$this->longitude = (!empty($_COOKIE['current_longitude'])) ? $_COOKIE['current_longitude'] : '';
	}
	public function notes_schema(){
		return parent::notes_schema();
	}
	public function create_form($subject_id, $aspect_type_id=null){
		return parent::create_form($subject_id, $this->aspect_type);
	}
	public function edit_form($id){
		return parent::edit_form($id);
	}
	public function display_aspect(){
		$output = '<p>Your current location is:<br/>latitude:'.$this->latitude.'<br/>longitude:'.$this->longitude.'</p>';
		$map_query_string = $this->latitude . ',' . $this->longitude;   //"40.714728,-73.998672"
		$output .=  \App\Http\Controllers\GoogleController::get_static_map($map_query_string);
		return $output;
	}
	public function parse(){}
}  // End of the GeolocationAspectclass.

class DarkskyForecastAspect extends GeolocationAspect{
	public function __construct(){
		parent::__construct();
		$this->title = 'Weather Forecast';
	}
	public function notes_schema(){
		return parent::notes_schema();
	}
	public function create_form($subject_id, $aspect_type_id=null){
		return parent::create_form($subject_id, $this->aspect_type);
	}
	public function edit_form($id){
		return parent::edit_form($id);
	}
	public function display_aspect(){
		//dd($this);
		//$output = parent::display_aspect();
		$output = '';
		$darksky_endpoint = 'https://api.darksky.net/forecast/';
		$darksky_query = $darksky_endpoint . env('DARKSKY_API_KEY') . '/' . $this->latitude . ',' . $this->longitude;
		$darksky_forecast_json = Ana::quick_curl($darksky_query);
		$darksky_forecast = json_decode($darksky_forecast_json, true);
		$output .= $this->assemble_output($darksky_forecast);
		//$output .= '<pre>' . var_export($darksky_forecast['daily'], true) . '</pre>';
		return $output;
	}
	public function parse(){
		parent::parse();
	}

	public function assemble_output($darksky_forecast, $forecast_type='daily'){
		$output = '';
		switch($forecast_type){
			case 'daily':
				$scope = $darksky_forecast['daily'];
				$output .= '<h5>'.$scope['summary'].'</h5>'.PHP_EOL;
				foreach ($scope['data'] as $day){
					$output .= '<div class="panel panel-default">'.PHP_EOL;
					$output .= '<div class="panel-body">'.PHP_EOL;
					$output .= '<strong>'. \Carbon\Carbon::createFromTimestamp($day['time'])->toFormattedDateString() . '</strong>'.PHP_EOL;
					$output .= '<li>'.$day['summary'].'</li>';
					$output .= '<li>Low: '.(int)$day['apparentTemperatureMin'].'</li>';
					$output .= '<li>High: '.(int)$day['apparentTemperatureMax'].'</li>';
					$output .= '<li>Humidity: '.(int)($day['humidity'] * 100).' %</li>';
					$output .= '<li>Sunrise: '.\Carbon\Carbon::createFromTimestamp($day['sunriseTime'])->toTimeString().'</li>';
					$output .= '<li>Sunset: '.\Carbon\Carbon::createFromTimestamp($day['sunsetTime'])->toTimeString().'</li>';
					$output .= '</div>'.PHP_EOL;
					$output .= '</div>'.PHP_EOL;
				}
		}
		return $output;
	}
}  // End of the DarkskyForecastAspectclass.

class WolframAlphaAspectAspect extends Aspect{
	public function __construct(){
		parent::__construct();
		$this->title = 'Wolfram Alpha Results';
	}

	public function notes_schema(){
		//return parent::notes_schema();
		$settings = json_decode(parent::notes_schema(), true);
		$settings['query'] = '';
		return json_encode($settings);
	}
	public function create_form($subject_id, $aspect_type_id=null){
		return parent::create_form($subject_id, $this->aspect_type);
	}
	public function edit_form($id){
		return parent::edit_form($id);
	}
	public function display_aspect(){
		$settings = (!is_null($this->aspect_notes)) ? json_decode($this->aspect_notes, true) : json_decode($this->notes_schema(), true);
		$output = parent::display_aspect();
		$output .= '<h5>'.$settings['query'].'<h5>';
		$wolfram = new \App\Http\Controllers\WolframAlphaController;
		$api_result = $wolfram->html_query($settings['query']);
		$output .= $api_result;
		return $output;
	}
	public function parse(){}
}  // End of the WolframAlphaAspectAspectclass.

class DemoAspect extends Aspect{
	public function notes_schema(){
		return parent::notes_schema();
	}
	public function create_form($subject_id, $aspect_type_id=null){
		return parent::create_form($subject_id, $this->aspect_type);
	}
	public function edit_form($id){
		return parent::edit_form($id);
	}
	public function display_aspect(){
		$output = parent::display_aspect();
		return $output;
	}
	public function parse(){}
}  // End of the DemoAspectclass.


// ---------------------------------------------- //


// ---------- End Custom Aspects ---------------- //