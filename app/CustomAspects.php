<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
use imonroe\crps\Aspect;
use imonroe\crps\AspectFactory;
use imonroe\crps\Subject;
use imonroe\crps\Ana;
use League\CommonMark\CommonMarkConverter;
use Validator;

// ---------  Begin Custom Aspects -------------- //

/*
Hey, you're going to see a line at the end of this file that looks like:

// ------------------------------------------- //

DO NOT REMOVE OR MODIFY THAT LINE.  IT IS THE FIND AND REPLACE TOKEN THAT ADDS NEW CUSTOM ASPECT CODE.
IF YOU REMOVE OR CHANGE IT, YOU WILL BREAK THE APP.
*/

/*  -- Basic Aspect Types -- */

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
    $form = \BootForm::horizontal(['url' => '/aspect/create', 'method' => 'post', 'files' => true]);
    $form .= \BootForm::hidden('subject_id', $subject_id);
    $form .= \BootForm::hidden('aspect_type', $aspect_type_id);
    $form .= \BootForm::hidden('media_collection', 'uploads');
    $form .= \BootForm::hidden('mime_type', 'all');
    $form .= \BootForm::text('title', 'Title');
    $form .= \BootForm::textarea('aspect_data', 'Description');
    //$form .= \BootForm::text('aspect_source', 'File');
    $form .= \BootForm::file('file_upload');
    $form .= $this->notes_fields();
    $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
    $form .= \BootForm::close();
    return $form;
	}
	public function edit_form($id){
    $media_items = $this->media;
    $output = '<h3>Currently attached files:</h3>';
    $output .= '<ul>'.PHP_EOL;
    foreach ($media_items as $file){
      $output .= '<li><a href="'.$file->getUrl().'">'.$file->name.' </a> ('.$file->mime_type.', '.$file->human_readable_size.')</li>';
    }
    $output .= '</ul>'.PHP_EOL;

    $form = \BootForm::horizontal(['url' => '/aspect/'.$this->id.'/edit', 'method' => 'post', 'files' => true]);
    $form .= \BootForm::hidden('aspect_id', $this->id);
    $form .= \BootForm::hidden('aspect_type', $this->aspect_type()->id);
    $form .= \BootForm::hidden('media_collection', 'uploads');
    $form .= \BootForm::hidden('mime_type', 'all');
    $form .= \BootForm::text('title', 'Title', $this->title);
    $form .= \BootForm::textarea('aspect_data', 'Description', $this->aspect_data);
    //$form .= \BootForm::text('aspect_source', 'Source', $this->aspect_source);
    //$form .= \BootForm::checkbox('hidden', 'Hidden?', $aspect->hidden);
    //$form .= \BootForm::file('file_upload', 'Add Another File');
    $form .= $this->notes_fields();
    $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
    $form .= \BootForm::close();

    $out = $output . $form;
    return $out;
	}
	public function display_aspect(){
    // There will be uploaded file(s) associated with this aspect, so let's grab them.
    $media_items = $this->media;
		$output = '<div class="aspect_type-'.$this->aspect_type()->id.'">';
		$output .= '<p>'.$this->aspect_data.'</p>'.PHP_EOL;
    $output .= '<ul>'.PHP_EOL;
    foreach ($media_items as $file){
      $output .= '<li><a href="'.$file->getUrl().'">'.$file->name.' </a> ('.$file->mime_type.', '.$file->human_readable_size.')</li>';
    }
    $output .= '</ul>'.PHP_EOL;
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
    // There will be uploaded file(s) associated with this aspect, so let's grab them.
    $media_items = $this->media;
		$css_size = '';
		$settings = (array) json_decode($this->aspect_notes);
		if ( !empty($settings['width']) ){
			$css_size = 'style="width:'.$settings['width'].';';
			if ( !empty($settings['height']) ){
				$css_size .= ' height:'.$settings['height'].';';
			}
			$css_size .= '"';
		}
    $output = '';
    foreach ($media_items as $file){
      $output .= '<div class="image_aspect_display">'.PHP_EOL;
      $output .= '<img src="'.$file->getUrl().'" '.$css_size.' />';
      $output .= '<div class="image_caption">'.$this->aspect_data.'</div>';
      $output .= '</div>'.PHP_EOL;
      $output .= '<p><a href="'.$file->getUrl().'">'.$file->name.' </a> ('.$file->mime_type.', '.$file->human_readable_size.')</p>';

    }
		return $output;
	}
	public function parse(){
		parent::parse();
	}
}

class UnformattedTextAspect extends Aspect{
	public function create_form($subject_id, $aspect_type_id=null){
    $form = \BootForm::horizontal(['url' => '/aspect/create', 'method' => 'post', 'files' => false]);
    $form .= \BootForm::hidden('subject_id', $subject_id);
    $form .= \BootForm::hidden('aspect_type', $aspect_type_id);
    $form .= \BootForm::text('title', 'Title');
    $form .= \BootForm::textarea('aspect_data', 'Unformatted Text');
    $form .= \BootForm::text('aspect_source', 'Source');
    //$form .= \BootForm::file('file_upload');
    $form .= $this->notes_fields();
    $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
    $form .= \BootForm::close();
    return $form;
	}
	public function edit_form($id){
    $aspect = Aspect::find($id);
    $form = \BootForm::horizontal(['url' => '/aspect/'.$id.'/edit', 'method' => 'post', 'files' => true]);
    $form .= \BootForm::hidden('aspect_id', $aspect->id);
    $form .= \BootForm::hidden('aspect_type', $aspect->aspect_type()->id);
    $form .= \BootForm::text('title', 'Title', $aspect->title);
    $form .= \BootForm::textarea('aspect_data', 'Unformatted Text', $aspect->aspect_data);
    $form .= \BootForm::text('aspect_source', 'Source', $aspect->aspect_source);
    //$form .= \BootForm::checkbox('hidden', 'Hidden?', $aspect->hidden);
    //$form .= \BootForm::file('file_upload');
    $form .= $this->notes_fields();
    $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
    $form .= \BootForm::close();
    return $form;
	}
	public function display_aspect(){
		$output = parent::display_aspect();
		return $output;
	}
	public function parse(){
                parent::parse();
        }

}

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
    $form = \BootForm::horizontal(['url' => '/aspect/create', 'method' => 'post', 'files' => true]);
    $form .= \BootForm::hidden('subject_id', $subject_id);
    $form .= \BootForm::hidden('aspect_type', $aspect_type_id);
    $form .= \BootForm::text('title', 'Title');
    $form .= \BootForm::hidden('aspect_data', 'Aspect Data');
    $form .= \BootForm::hidden('aspect_source');
    $form .= \BootForm::hidden('hidden', 'Hidden?');
    $form .= \BootForm::hidden('file_upload');
    $form .= $this->notes_fields();
    $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
    $form .= \BootForm::close();
		$output .= $form;
    return $output;
	}
	public function edit_form($id){
		$output = $this->display_aspect() . '<hr />';
    $aspect = Aspect::find($id);
    $form = \BootForm::horizontal(['url' => '/aspect/'.$id.'/edit', 'method' => 'post', 'files' => true]);
    $form .= \BootForm::hidden('aspect_id', $aspect->id);
    $form .= \BootForm::hidden('aspect_type', $aspect->aspect_type()->id);
    $form .= \BootForm::hidden('title', 'Title', $aspect->title);
    $form .= \BootForm::hidden('aspect_data', 'Aspect Data', $aspect->aspect_data);
    $form .= \BootForm::hidden('aspect_source', 'Source', $aspect->aspect_source);
    $form .= \BootForm::hidden('hidden', 'Hidden?', $aspect->hidden);
    $form .= \BootForm::hidden('file_upload');
    $form .= $this->notes_fields();
    $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
    $form .= \BootForm::close();
    return $form;
        return $output;
	}
	public function display_aspect(){
		$output = $this->lambda_function();
		return $output;
	}

	public function parse(){
                parent::parse();
        }


	public function lambda_function(){
		return 'lambda_function output';
	}
}  // End of the LamdaFunctionAspectclass.

class MarkdownTextAspect extends UnformattedTextAspect
{
    public function create_form($subject_id, $aspect_type_id = null)
    {
        return parent::create_form($subject_id, $this->aspect_type);
    }
    public function edit_form($id)
    {
        return parent::edit_form($id);
    }
    public function display_aspect()
    {
        $markdown_converter = new CommonMarkConverter();
        $output = $markdown_converter->convertToHtml($this->aspect_data);
        return $output;
    }
    public function parse(){
                parent::parse();
        }

}  // End of the MarkdownTextAspectclass.

class FormattedTextAspect extends UnformattedTextAspect
{
    public function create_form($subject_id, $aspect_type_id = null)
    {
      $form = \BootForm::horizontal(['url' => '/aspect/create', 'method' => 'post', 'files' => false]);
      $form .= \BootForm::hidden('subject_id', $subject_id);
      $form .= \BootForm::hidden('aspect_type', $aspect_type_id);
      $form .= \BootForm::text('title', 'Title');
      $form .= \BootForm::textarea('aspect_data', 'Formatted Text', '', ['class' => 'wysiwyg_editor', 'style' => 'width:100%;']);
      $form .= \BootForm::text('aspect_source', 'Source');
      //$form .= \BootForm::file('file_upload');
      $form .= $this->notes_fields();
      $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
      $form .= \BootForm::close();
      return $form;
  	}
  	public function edit_form($id){
      $aspect = Aspect::find($id);
      $form = \BootForm::horizontal(['url' => '/aspect/'.$id.'/edit', 'method' => 'post', 'files' => true]);
      $form .= \BootForm::hidden('aspect_id', $aspect->id);
      $form .= \BootForm::hidden('aspect_type', $aspect->aspect_type()->id);
      $form .= \BootForm::text('title', 'Title', $aspect->title);
      $form .= \BootForm::textarea('aspect_data', 'Formatted Text', $aspect->aspect_data, ['class' => 'wysiwyg_editor', 'style' => 'width:100%;']);
      $form .= \BootForm::text('aspect_source', 'Source', $aspect->aspect_source);
      //$form .= \BootForm::checkbox('hidden', 'Hidden?', $aspect->hidden);
      //$form .= \BootForm::file('file_upload');
      $form .= $this->notes_fields();
      $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
      $form .= \BootForm::close();
      return $form;
  	}
    public function display_aspect()
    {
        $output = parent::display_aspect();
        return $this->aspect_data;
    }
    public function parse(){
                parent::parse();
        }

}  // End of the FormattedTextAspectclass.

class RelationshipAspect extends Aspect
{
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

    public function notes_schema()
    {
      $settings = json_decode(parent::notes_schema(), true);
      $settings['reciprocal_aspect_id'] = '';
      return json_encode($settings);
    }
    public function create_form($subject_id, $aspect_type_id = null)
    {
        $form = \BootForm::horizontal(['url' => '/aspect/create', 'method' => 'post', 'files' => false]);
        $form .= \BootForm::hidden('subject_id', $subject_id);
        $form .= \BootForm::hidden('aspect_type', $aspect_type_id);
        $form .= \BootForm::hidden('title', '');
        $form .= \BootForm::label('query', 'Related to: ');
        $form .= '<subject-autocomplete-field></subject-autocomplete-field>';
        $form .= \BootForm::text('aspect_data', 'As:');
        $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
        $form .= \BootForm::close();
        return $form;
    }
    public function edit_form($id)
    {
        $current_aspect = Aspect::find($id);
        $form = \Form::open(['url' => '/aspect/'.$id.'/edit', 'method' => 'post', 'files' => false]);
        $form .= \Form::hidden('subject_id', $current_aspect->subjects()->first()->id);
        $form .= \Form::hidden('aspect_type', $current_aspect->aspect_type);
        $form .= '<p>';
        $form .= \BootForm::label('aspect_source', 'Related to: ');
        $form .= '<subject-autocomplete-field id="aspect_source" name="aspect_source" v-bind:initial_value="'.$current_aspect->aspect_source.'"></subject-autocomplete-field>';
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
    public function display_aspect()
    {
        //$output = parent::display_aspect();
        $target = Subject::where('name', '=', $this->aspect_source)->first();
        $output = '<p>Related to <a href="/subject/'.$target->id.'">'.$this->aspect_source.'</a> as '.$this->aspect_data.'</p>';
        return $output;
    }

    // Hey, instead of using parse to make the reciprocal aspect, let's use our neat hooks
    // Also, when we delete this aspect, we should delete the reciprocal one as well.
    public function pre_save(Request &$request)
    {
        // we need to ensure that we're pointing at a subject that exists,
        // so we'll do a little validation here.
        Validator::make($request->all(), [
            'query' => 'exists:subjects,name',
        ], [
            'exists' => 'You cannot link to a subject that doesn\'t exist.',
        ])->validate();

        // We are using a non-standard form input id to hold our target subject name,
        // So we'll set it correctly here before we save.
        $this->aspect_source = $request->input('query');
    }
    public function post_save(Request &$request)
    {
        // Let's check to see if there's a reciprocal aspect.
        $settings = $this->get_aspect_notes_array();
        if (empty($settings['reciprocal_aspect_id'])){

          Log::info('Creating a reciprocal relationship aspect.');

          $target = Subject::where('name', '=', $this->aspect_source)->first();
          $this_subject = $this->subjects()->first();

          $new_aspect = AspectFactory::make_from_aspect_type($this->aspect_type);
          $new_aspect->aspect_source = $this_subject->name;
          $new_aspect->aspect_data = $this->aspect_data;
          $new_aspect->aspect_notes = ['reciprocal_aspect_id' => $this->id];
          $new_aspect->user = $this->user;
          $new_aspect->save();
          $target->aspects()->attach($new_aspect->id);
          $settings['reciprocal_aspect_id'] = $new_aspect->id;
          $this->aspect_notes = $settings;
          $this->save();

        } else {
          return;
        }
    }
    public function pre_update(Request &$request)
    {
        return false;
    }
    public function post_update(Request &$request)
    {
        return false;
    }
    public function pre_delete(Request &$request)
    {
      // If we are deleting one end of the relationship, we also want to delete the other end.
      $settings = $this->get_aspect_notes_array();
      Aspect::where('id', $settings['reciprocal_aspect_id'])->delete();
    }


    public function parse(){
                parent::parse();
        }

}  // End of the RelationshipAspectclass.

/*  -- End Basic Aspect Types -- */

/*  -- Begin App-specific Aspects -- */


// ---------------------------------------------- //


// ---------- End Custom Aspects ---------------- //
