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
		$output .= \App\Http\Controllers\GoogleController::get_static_map($formatted_address);
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

// default custom class created automatically.

class GoogleContactsAPIResultsAspect extends APIResultAspect{
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
		$output = '<p>Google Contacts API cached results</p>';
		$decoded = json_decode($this->aspect_data, true);
		//$decoded_str  = var_export($decoded, true);
		//dd($decoded);
		if (!empty($decoded['feed']['entry'] )){
			foreach ($decoded['feed']['entry'] as $contact){
				$output .= '<p><pre>'.var_export($contact, true).'</pre></p>'.PHP_EOL;
			}
		}
		//$output .= parent::display_aspect();
		return $output;
	}
	public function parse(){
		if (empty($this->last_parsed) || strtotime($this->last_parsed) < strtotime('now -1 hour') ){
		//if (true){
			Log::info('Parsing Google Contacts API Results Aspect');
			$google_agent = new \App\Http\Controllers\GoogleController;
			$agent_response = $google_agent->get_contacts();
			if (!is_null($agent_response)){
				$this->aspect_data = $google_agent->get_contacts();
				$this->last_parsed = Carbon::now();
				$this->update_aspect();
				$this->update_people_subjects();
			}
			Log::info('Finished Parsing Google Contacts API Results Aspect');
		}
	}

	public function update_people_subjects(){
		$decoded = json_decode($this->aspect_data, true);
		foreach ($decoded['feed']['entry'] as $contact){
			//$output .= '<p><pre>'.var_export($contact, true).'</pre></p>'.PHP_EOL;
			$subject_name = (!empty($contact['title']['$t'])) ? $contact['title']['$t'] : null;
			Log::info('Trying to check a subject name like: '.$subject_name);
			if ($subject_name){
				$subject_exists = Subject::exists($subject_name);
				if (!$subject_exists){
					// try and create it.
					Log::info('Adding a new Google Contact.');
					$subject_type = SubjectType::where('type_name', '=', 'People')->first();
					$new_subject = new Subject;
					$new_subject->name = $subject_name;
					$new_subject->subject_type = $subject_type->id;
					$new_subject->save();

					// add the google contact results as an aspect
					$aspect_type_id = AspectType::where('aspect_name', '=', 'Google Contact Data')->first()->id;
					$contact_aspect = AspectFactory::make_from_aspect_type($aspect_type_id);
					$contact_aspect->aspect_data = json_encode($contact);
					$contact_aspect->save();
					$new_subject->aspects()->attach($contact_aspect->id);
				} 
				else {
					Log::info($subject_name . ' already exists.');
					// check to see if you should update it.
				}
			}
		}
	}

}  // End of the GoogleContactsAPIResultsAspectclass.

// default custom class created automatically.

class GoogleContactDataAspect extends Aspect{
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
		$contact_array = json_decode($this->aspect_data, true);
		$output = '';
		if (!empty($contact_array['link'])){
			foreach ($contact_array['link'] as $link){
				if ( $link['type'] == 'image/*' ) {
					$profile_image = $link['href'];
				}
				if ( $link['type'] == 'self'  ){
					$contact_link = $link['href'];
				}
				if ( $link['type'] == 'edit'  ){
					$edit_link = $link['href'];
				}
			}
		}
		$full_name = $contact_array['gd$name']['gd$fullName']['$t'];
		$gender = (!empty($contact_array['gContact$gender']['value'])) ? $contact_array['gContact$gender']['value'] : null;
		$birthday = (!empty($contact_array['gContact$birthday']['when'])) ? $contact_array['gContact$birthday']['when'] : null; 
		$organizations = array(); 
		if (!empty($contact_array['gd$organization'])){
			foreach ($contact_array['gd$organization'] as $org){
				$organizations[$org['gd$orgName']['$t']] = (!empty($org['gd$orgTitle']['$t'])) ? $org['gd$orgTitle']['$t'] : null ;
			}
		}
		$email_addresses = array();
		if (!empty($contact_array['gd$email'])){
			foreach ($contact_array['gd$email'] as $email){
			$email_addresses[] = '<a href="mailto:'.$email['address'].'">'.$email['address'].'</a>';
		}
		}
		$phone_numbers = array();
		if (!empty($contact_array['gd$phoneNumber'])){
			foreach ($contact_array['gd$phoneNumber'] as $phone){
			$phone_numbers[] = '<a href="'.$phone['uri'].'">'.$phone['$t'].'</a>';
		}
		}
		$mailing_addresses = array();
		if (!empty($contact_array['gd$structuredPostalAddress'])){
			foreach ($contact_array['gd$structuredPostalAddress'] as $address){
			$add = array();
			$add['formatted'] = (!empty($address['gd$formattedAddress']['$t']))  ? $address['gd$formattedAddress']['$t'] : null;
			$add['street'] = (!empty($address['gd$street']['$t']))  ? $address['gd$street']['$t'] : null;
			$add['city'] = (!empty($address['gd$city']['$t']))  ? $address['gd$city']['$t'] : null;
			$add['state'] = (!empty($address['gd$region']['$t']))  ? $address['gd$region']['$t'] : null;
			$add['zip'] = (!empty($address['gd$postcode']['$t']))  ? $address['gd$postcode']['$t'] : null;
			$add['google_maps_link'] =  \App\Http\Controllers\GoogleController::get_static_map($add['formatted']);
			$mailing_addresses[] = $add;
		}
		}
		$websites = array();
		if (!empty($contact_array['gContact$website'])){
			foreach ($contact_array['gContact$website'] as $website){
			$websites[] = '<a href="'.$website['href'].'" target="_blank">'.$website['href'].'</a>';
		}
		}
		$output .= '<div class="google-contact-info">'.PHP_EOL;

		if (isset($profile_image) && false){
			$google_agent = new \App\Http\Controllers\GoogleController;
			$authenticated_reply = $google_agent->get_authenticated_url($profile_image);
			//dd($authenticated_reply);
			$output .= '<div class="google-contact-profile-photo" style="width:50%; margin:10px; float:left;">'.PHP_EOL;
			$output .= '<img src = "'.$authenticated_reply.'" style="width:100%;">'.PHP_EOL;
			$output .= '</div>'.PHP_EOL;
		}

		$output .= '<p>Full Name: '.$full_name.'</p>'.PHP_EOL;
		$output .= '<p>Gender: '.$gender.'</p>'.PHP_EOL;
		$output .= '<p>DOB: '.$birthday.'</p>'.PHP_EOL;
		$output .= '<p>Organizations: </p>'.PHP_EOL;
		$output .= '<ul>'.PHP_EOL;
		if (!empty($organizations)){
			foreach ($organizations as $key => $value){
				$output .= '<li>'.$key.' - '.$value.'</li>'.PHP_EOL;
			}
		}
		$output .= '</ul>'.PHP_EOL;
		$output .= '<p>Email Addresses: </p>'.PHP_EOL;
		$output .= '<ul>'.PHP_EOL;
		foreach ($email_addresses as $key => $value){
			$output .= '<li>'.$value.'</li>'.PHP_EOL;
		}
		$output .= '</ul>'.PHP_EOL;

		$output .= '<p>Phone Numbers: </p>'.PHP_EOL;
		$output .= '<ul>'.PHP_EOL;
		foreach ($phone_numbers as $key => $value){
			$output .= '<li>'.$value.'</li>'.PHP_EOL;
		}
		$output .= '</ul>'.PHP_EOL;

		$output .= '<p>Mailing Addresses: </p>'.PHP_EOL;
		$output .= '<ul>'.PHP_EOL;
		foreach ($mailing_addresses as $key => $value){
			$output .= '<li>'.$value['formatted'];
			if (!empty($value['google_maps_link'])){
				$output .= $value['google_maps_link'];
			}
			$output .= '</li>'.PHP_EOL;
		}
		$output .= '</ul>'.PHP_EOL;

		$output .= '<p>Web Sites: </p>'.PHP_EOL;
		$output .= '<ul>'.PHP_EOL;
		foreach ($websites as $key => $value){
			$output .= '<li>'.$value.'</li>'.PHP_EOL;
		}
		$output .= '</ul>'.PHP_EOL;

		//$output .= '<a href="'.$edit_link.'" class="btn btn-primary">Edit with Google</a>';

		$output .= '</div>'.PHP_EOL;
		//$output .= '<pre>'.var_export($contact_array, true).'</pre>';
		return $output;
	}

	public function parse(){}
}  // End of the GoogleContactDataAspectclass.

// default custom class created automatically.

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

// default custom class created automatically.

class GoogleTasksListAspect extends LamdaFunctionAspect{
	public function notes_schema(){
		//array('list_id'=>'', 'list_title'=>'Today\'s TODO List', 'css_id'=>'1')
		$settings = json_decode(parent::notes_schema(), true);
		$settings['list_id'] = '@default';
		$settings['list_title'] = 'Todo List';
		$settings['css_id'] = 'default_todo';
		return json_encode($settings);
	}
	public function create_form($subject_id, $aspect_type_id=null){
		$lists_agent =  new \App\Http\Controllers\GoogleController;
		$output = '';
		$lists_object = $lists_agent->get_all_task_lists();
		$lists_array = $lists_object->items;
		//dd($lists_array);
		$output .= '<p>Available Google Task Lists: </p>'.PHP_EOL;
		$output .= '<ul>'.PHP_EOL;
		foreach ($lists_array as $l){
			$output .= '<li>'.$l['title'].' -- list_id: '.$l['id'].'</li>'.PHP_EOL;
		}
		$output .= '</ul>'.PHP_EOL;
		$output .= parent::create_form($subject_id, $this->aspect_type);
		return $output;
	}
	public function edit_form($id){
		return parent::edit_form($id);
	}
	public function display_aspect(){
		$settings = (!is_null($this->aspect_notes)) ? json_decode($this->aspect_notes, true) : json_decode($this->notes_schema(), true);
		if (empty($settings['list_id']) || $settings['list_id'] == '@default'){
			$function_id = '';
		} else {
			$function_id = $settings['list_id'];
		}
		$spinner = '<center>'. Ana::loading_spinner() . '</center>';
		$csfr_token = csrf_token();
		$output = <<<OUTPUT_STRING
<div class="widget" id="todo_list_{$settings['css_id']}">
		<h3>{$settings['list_title']}</h3>
        <form class="form-inline" id="new_task_form_{$settings['css_id']}">
          <input name="due" type="hidden" value="" />
		  <input name="_token" type="hidden" value="$csfr_token" >
          <input name="task_list" type="hidden" value="{$function_id}" >
          <input name="action" type="hidden" value="new_todo_item" >
          <input name="new_task_title" type="text" class="form-control" id="new_task_title" placeholder="Add a new task">
          <button type="submit" class="btn" id="new_task_submit">Submit</button>
        </form>
    	<div id="todo_stage_{$settings['css_id']}" style="">$spinner</div>
        <script type="text/javascript">
			$(function(){
				$("#new_task_form_{$settings['css_id']}").submit(function(event){
					event.preventDefault();
					var fd = $(this).serialize();
					console.log(fd);
					var url = '/gtasks';
					$.ajax({
							type: 'POST',
							mimeType: 'multipart/form-data',
							url: url,
							data: fd
					})
					.done(function(html){
							$.get( "/gtasks/{$function_id}")
								.done(function( data ) {
									$("#todo_stage_{$settings['css_id']}").html(data);
								});
							$("#new_task_form_{$settings['css_id']}").trigger("reset");
					});
				});// end task submit
				$.get( "/gtasks/{$function_id}")
            			.done(function( data ) {
            			$("#todo_stage_{$settings['css_id']}").html(data);
        		});
			});

			function closeTodoItem_$function_id(item){
				var item_id = item.getAttribute('id');
				$.post( "/gtasks", 
						{ action: "complete_todo_item", task_id: item_id, _token: '$csfr_token', list_id:'{$function_id}'})
            			.done(function( data ) {
            				$.get( "/gtasks/{$function_id}")
							.done(function( data ) {
							$("#todo_stage_{$settings['css_id']}").html(data);
						});
        		});
			}
        </script>
</div>
OUTPUT_STRING;
		return $output;
	}
	public function parse(){}
	public function lambda_function(){
		return 'lambda_function output';
	}
}  // End of the GoogleTasksListAspectclass.

// default custom class created automatically.

class GoogleCalendarAspect extends LamdaFunctionAspect{

	public function notes_schema(){
		$settings = json_decode(parent::notes_schema(), true);
		$settings['calendar_title'] = 'Calendar';
		$settings['calendar_id'] = 'primary';
		$settings['css_id'] = 'default_todo';
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
		$calendar_title = $settings['calendar_title'];
		$csfr_token = csrf_token();
		$spinner = '<center>'.Ana::loading_spinner().'</center>';
		$output = '';
		$output .= <<<OUTPUT_STRING

<div class="widget" id="google_calendar_{$settings['calendar_id']}">
   
   <div id="calendar_display_{$settings['calendar_id']}" style="float:left; width:255px;margin:.25em;"></div>

   <div style="float:left; clear:none; margin:.5em;">
	<form class="form-inline" id="new_appointment_form" style="margin-left:15px;">
          <div class="form-group">
          <input name="calendar_id" type="hidden" value="{$settings['calendar_id']}" />
          <input name="action" type="hidden" value="new_appointment" />
 		  <input name="_token" type="hidden" value="$csfr_token" />
          <input name="new_appointment_txt" type="text" class="form-control" id="new_appointment_text" placeholder="Add a new appointment">
          <button type="submit" class="btn" id="new_appointment_submit">Submit</button>
          </div>
        </form>
        <div id="calendar_stage">
			$spinner
		</div>
	</div>
		<div style="clear:both;"></div>
        <script type="text/javascript">
			$(function(){
			    // display datepicker
				$("#calendar_display_{$settings['calendar_id']}").datepicker();
				$("#new_appointment_form").submit(function(event){
					event.preventDefault();
					var fd = $(this).serialize();
					var url = '/gcal';
					$.ajax({
							type: 'POST',
							mimeType: 'multipart/form-data',
							url: url,
							data: fd
					})
					.done(function(html){
							$.get( "/gcal")
								.done(function( data ) {
									$("#calendar_stage").html(data);
								});
							$("#new_appointment_form").trigger("reset");
					});
				});// end task submit

				 $.get( "/gcal")
					  .done(function( data ) {
					  $("#calendar_stage").html(data);
				  });
			});
        </script>
</div>		
OUTPUT_STRING;
		return $output;
	}
	public function parse(){}

	public function lambda_function(){
		return 'lambda_function output';
	}
}  // End of the GoogleCalendarAspectclass.

// default custom class created automatically.

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

// default custom class created automatically.

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

// default custom class created automatically.

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

// default custom class created automatically.

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

// default custom class created automatically.

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


// ---------------------------------------------- //


// ---------- End Custom Aspects ---------------- //