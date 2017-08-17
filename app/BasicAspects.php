<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use imonroe\crps\Aspect;
use imonroe\crps\AspectType;
use imonroe\crps\AspectFactory;
use imonroe\crps\Subject;
use imonroe\crps\SubjectType;
use imonroe\ana\Ana;

// ---------- Begin Basic Aspects ---------------- //
class DefaultAspect extends Aspect{
	public function __construct(){
		parent::__construct();
		// e.g., $this->keep_history = false; 
	}
	public function notes_schema(){
		$schema = json_decode(parent::notes_schema(), true);
		// e.g., $schema['webpage_url'] = '';
		return json_encode($schema);
	}

	public function create_form($subject_id, $aspect_type_id=null){
		return parent::create_form($subject_id, $aspect_type_id);
	}

	public function edit_form($id){
		return parent::edit_form($id);
	}

	public function display_aspect(){
		$output = parent::display_aspect();
		return $output;
	}
	public function parse(){
		$output = parent::parse();
		return $output;
	}
}

class FileUploadAspect extends Aspect{

	public function create_form($subject_id, $aspect_type_id=null){
		$form = \Form::open(['url' => '/aspect/create', 'method' => 'post', 'files' => true]);
		$form .= \Form::hidden('subject_id', $subject_id);
		$form .= \Form::hidden('aspect_type', $aspect_type_id );
		$form .= '<p>';
		$form .= \Form::label('title', 'Title: ');
		$form .= \Form::text('title');
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('aspect_data', 'Description: ');
		$form .= \Form::textarea('aspect_data');
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('file_upload', 'File Upload: ');
		$form .= \Form::file('file_upload');
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('aspect_source', 'Source: ');
		$form .= \Form::text('aspect_source');
		$form .= '</p>';

		$form .= $this->notes_fields();

		$form .= '<p>' . \Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
        return $form;
	}
	public function edit_form($id){
		return parent::edit_form($id);
	}
	public function display_aspect(){
		$output = '<div class="aspect_type-'.$this->aspect_type()->id.'">';
		$output .= '<h4>'.$this->title.'</h4>';
		$output .= '<p>Description: '.$this->aspect_data.'</p>'.PHP_EOL;
		$output .= '<p><a href="'.$this->aspect_source.'">'.$this->title.'</a></p>';
		$output .= '</div>';
		return $output;
	}
	public function parse(){}
}

class ImageAspect extends FileUploadAspect{

	public function notes_schema(){
		$settings = json_decode(parent::notes_schema(), true);
		$settings['width'] = '';
		$settings['height'] = '';
		$settings['css_class'] = '';
		return json_encode($settings);
	}

	public function create_form($subject_id, $aspect_type_id=null){
		return parent::create_form($subject_id, $this->aspect_type);
	}
	public function edit_form($id){
		return parent::edit_form($id);
	}

	public function css_size(){
		$css_string = 'width:'.$this->width.';';
		if (!is_null($this->height)){
			$css_string .= 'height:'.$this->height.';';
		}
		return $css_string;
	}

	public function css_class(){
		return (!is_null($this->css_class)) ? 'class="'.$this->css_class.'" ' : '';
	}

	public function display_aspect(){
		$css_size = '';
		$settings = (array) json_decode($this->aspect_notes);
		if ( !empty($settings['width']) ){
			$css_size = 'style="width:'.$settings['width'].';';
			if ( !empty($settings['height']) ){
				$css_size .= ' height:'.$settings['height'].';';
			}
			$css_size .= '"';
		}
		if ( !empty($settings['css_class']) ){
			$css_size .= ' class="'.$settings['css_class'].'"';
		}
		$output = '<h4>'.$this->title.'</h4>';
		$output .= '<img src="'.$this->aspect_source.'" '.$css_size.' />';
		$output .= '<div class="image_caption">'.$this->aspect_data.'</div>';
		$output .= '<p><a href="'.$this->aspect_source.'">Uploaded File</a></p>';

		return $output;
	}
	public function parse(){}
}

class UnformattedTextAspect extends Aspect{
	public function create_form($subject_id, $aspect_type_id=null){
		$form = \Form::open(['url' => '/aspect/create', 'method' => 'post', 'files' => true]);
		$form .= \Form::hidden('subject_id', $subject_id);
		$form .= \Form::hidden('aspect_type', $aspect_type_id );
		$form .= '<p>';
		$form .= \Form::label('title', 'Title: ');
		$form .= \Form::text('title');
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('aspect_data', 'Text: ');
		$form .= '<br />';
		$form .= \Form::textarea('aspect_data', null, ['style' => 'width:100%;']);
		$form .= '</p>';
		$form .= \Form::hidden('file_upload');
		$form .= '<p>';
		$form .= \Form::label('aspect_source', 'Source: ');
		$form .= \Form::text('aspect_source');
		$form .= '</p>';
		$form .= $this->notes_fields();
		$form .= '<p>' . \Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
        return $form;
	}
	public function edit_form($id){
		//$aspect = Aspect::findOrFail($id);
        $form = '';
		$form .= \Form::open(['url' => '/aspect/'.$id.'/edit', 'method' => 'post']);
		$form .= \Form::hidden('aspect_id', $id);
		$form .= \Form::hidden( 'aspect_type', $this->aspect_type()->id );
		$form .= '<p>';
		$form .= \Form::label('title', 'Title: ');
		$form .= \Form::text('title', $this->title);
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label( 'aspect_data', 'Unformatted Text: ' );
		$form .= '<br />';
		$form .= \Form::textarea('aspect_data', $this->aspect_data);
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('aspect_source', 'Source: ');
		$form .= \Form::text('aspect_source', $this->aspect_source);
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('hidden', 'Hidden?: ');
		$form .= ( $this->hidden) ? \Form::checkbox('hidden', '1', true) : \Form::checkbox('hidden', '1', false);;
		$form .= '</p>';
		$form .= \Form::hidden('file_upload');
		$form .= $this->notes_fields();
		$form .= '<p>' . \Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
        return $form;
	}
	public function display_aspect(){
		$output = parent::display_aspect();
		return $output;
	}
	public function parse(){}
}  // End of the UnformattedTextAspectclass.

class MarkdownTextAspect extends UnformattedTextAspect{
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
}  // End of the MarkdownTextAspectclass.

class FormattedTextAspect extends UnformattedTextAspect{
	public function create_form($subject_id, $aspect_type_id=null){
		$form = \Form::open(['url' => '/aspect/create', 'method' => 'post', 'files' => true]);
		$form .= \Form::hidden('subject_id', $subject_id);
		$form .= \Form::hidden('aspect_type', $aspect_type_id );
		$form .= '<p>';
		$form .= \Form::label('title', 'Title: ');
		$form .= \Form::text('title');
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('aspect_data', 'Text: ');
		$form .= '<br />';
		$form .= \Form::textarea('aspect_data', null, ['class' => 'wysiwyg_editor', 'style' => 'width:100%;']);
		$form .= '</p>';
		$form .= \Form::hidden('hidden', '0');
		$form .= \Form::hidden('file_upload');
		$form .= '<p>';
		$form .= \Form::label('aspect_source', 'Source: ');
		$form .= \Form::text('aspect_source');
		$form .= '</p>';
		$form .= $this->notes_fields();
		$form .= '<p>' . \Form::submit('Submit', ['class' => 'btn btn-primary']). '</p>';
		$form .= \Form::close();
		$form .= '<p>Class: '.get_class ($this).'</p>';
        return $form;
	}
	public function edit_form($id){
		$current_aspect = Aspect::find($id);
		$form = \Form::open(['url' => '/aspect/'.$id.'/edit', 'method' => 'post', 'files' => false]);
		$form .= \Form::hidden('subject_id', $current_aspect->subjects()->first()->id);
		$form .= \Form::hidden('aspect_type', $current_aspect->aspect_type()->id );
		$form .= '<p>';
		$form .= \Form::label('title', 'Title: ');
		$form .= \Form::text('title', $current_aspect->title);
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('aspect_data', 'Text: ');
		$form .= '<br />';
		$form .= \Form::textarea('aspect_data', $current_aspect->aspect_data, ['class' => 'wysiwyg_editor', 'style' => 'width:100%;']);
		$form .= '</p>';
		$form .= \Form::hidden('hidden', $current_aspect->hidden);
		$form .= \Form::hidden('file_upload');
		$form .= '<p>';
		$form .= \Form::label('aspect_source', 'Source: ');
		$form .= \Form::text('aspect_source', $current_aspect->aspect_source);
		$form .= '</p>';
		$form .= $this->notes_fields();
		$form .= '<p>' . \Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();

        return $form;
	}
	public function display_aspect(){
		$output = parent::display_aspect();
		return $this->aspect_data;
	}
	public function parse(){}
}  // End of the FormattedTextAspectclass.

class APIResultAspect extends Aspect{
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
		$output = "<strong>API Result: <strong>";
		$output .= '<pre>';
		$output .= $this->aspect_data;
		$output .= '</pre>';
		return $output;	
	}
	public function parse(){}
}  // End of the APIResultAspectclass.

class RelationshipAspect extends Aspect{
	/*
		So, for relationships, this is how we're going to handle it.
		When you create a relationship from Subject A to Subject B, it will happen twice.
		First, you create the relationship on A.
		The ID of the thing that it is related to will be stored in the aspect_source field.
		The description of the relationship will be stored in the aspect_data field.
		Then, in the parse function, we'll look for a matching relationship.
		If we don't find one, we'll create one that describes the reverse relationship.
		SO:
			create the relationship. Store the ID of the target.
			upon parse, see if there is a relationship aspect type on the target that references 
			  this id.
			if that doesn't exist, create it. Copy the description from the aspect_data field.
		When you display it, display the relationship description and title of the target as a link.
	*/
	function __construct(){
		parent::__construct();
		$this->keep_history = false;
	}

	public function notes_schema(){
		return parent::notes_schema();
	}
	public function create_form($subject_id, $aspect_type_id=null){
		$form = \Form::open(['url' => '/aspect/create', 'method' => 'post', 'files' => true]);
		$form .= \Form::hidden('subject_id', $subject_id);
		$form .= \Form::hidden('aspect_type', $aspect_type_id );
		$form .= '<p>';
		$form .= \Form::label('aspect_source', 'Related to: ');
		$form .= \Form::text('aspect_source', null, ['class' => 'subject-autocomplete'] );
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('aspect_data', 'As:');
		$form .= \Form::text('aspect_data');
		$form .= '<p>';
		$form .= \Form::hidden('predicted_accuracy', '');
		$form .= \Form::hidden('hidden', '0');
		$form .= \Form::hidden('file_upload');
		$form .= $this->notes_fields();
		$form .= '<p>' .\Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
        return $form;
	}
	public function edit_form($id){
		$current_aspect = Aspect::find($id);
		$form = \Form::open(['url' => '/aspect/'.$id.'/edit', 'method' => 'post', 'files' => false]);
		$form .= \Form::hidden('subject_id', $current_aspect->subjects()->first()->id);
		$form .= \Form::hidden('aspect_type', $current_aspect->aspect_type );
		$form .= '<p>';
		$form .= \Form::label('aspect_source', 'Related to: ');
		$form .= \Form::text('aspect_source', $current_aspect->aspect_source, ['class' => 'subject-autocomplete'] );
		$form .= '</p>';
		$form .= '<p>';
		$form .= \Form::label('aspect_data', 'As:');
		$form .= \Form::text('aspect_data', $current_aspect->aspect_data);
		$form .= '<p>';
		$form .= \Form::hidden('hidden', '0');
		$form .= \Form::hidden('file_upload');
		$form .= $this->notes_fields();
		$form .= '<p>' .\Form::submit('Submit', ['class' => 'btn btn-primary']) . '</p>';
		$form .= \Form::close();
        return $form;
	}
	public function display_aspect(){
		//$output = parent::display_aspect();
		$target = Subject::where('name', '=', $this->aspect_source)->first();
		$output = '<p>Related to <a href="/subject/'.$target->id.'">'.$this->aspect_source.'</a> as '.$this->aspect_data.'</p>';
		return $output;
	}
	public function parse(){
		//throw new \Exception('Fake Exception.');
		// check once an hour.
		if (empty($this->last_parsed) || strtotime($this->last_parsed) < strtotime('now -1 hour') ){
			Log::info('entered parse function for Relationship Aspect: '.$this->id);
			// create a reciprocal relationship, if one does not exist.
			Log::info('Checking for a reciprocal relationship aspect.');
			$target = Subject::where('name', '=', $this->aspect_source)->first();
			$this_subject = $this->subjects()->first();
			$check_aspect = false;
			foreach ($target->aspects as $a){
				if ( ($a->aspect_type = $this->aspect_type) && ($a->aspect_source == $this_subject->name) ){
					$check_aspect = true;
				}
			}
			if (!$check_aspect){
				Log::info('Creating a reciprocal relationship aspect.');
				$new_aspect = AspectFactory::make_from_aspect_type($this->aspect_type);
				$new_aspect->aspect_source = $this_subject->name;
				$new_aspect->aspect_data = $this->aspect_data;
				$new_aspect->save();
				$target->aspects()->attach($new_aspect->id);
			} else {
				Log::info('Reciprocal relationship aspect already exists.');
			}
			$this->last_parsed = Carbon::now();
			$this->update_aspect();				
		}
	}
}  // End of the RelationshipAspectclass.

class LamdaFunctionAspect extends Aspect{
	function __construct(){
		parent::__construct();
		$this->keep_history = false;
	}

	public function notes_schema(){
		return parent::notes_schema();
	}

	public function create_form($subject_id, $aspect_type_id=null){
		$output = $this->display_aspect() . '<hr />';
		$form = \Form::open(['url' => '/aspect/create', 'method' => 'post', 'files' => true]); 
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
		$output .= $form;
        return $output;
	}
	public function edit_form($id){
		$output = $this->display_aspect() . '<hr />';
		$form = \Form::open(['url' => '/aspect/'.$this->id.'/edit', 'method' => 'post', 'files' => false]); 
		$form .= \Form::hidden('subject_id', $this->subject_id);
		$form .= \Form::hidden('aspect_type', $this->aspect_type );
		$form .= '<p>';
		$form .= \Form::label('title', 'Title: ');
		$form .= \Form::text('title', $this->title);
		$form .= '</p>';
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
		$output = $this->lambda_function();
		return $output;
	}
	public function parse(){}

	public function lambda_function(){
		return 'lambda_function output';
	}
}  // End of the LamdaFunctionAspectclass.


// ---------- End Basic Aspects ---------------- //