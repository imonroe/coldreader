<?php

namespace imonroe\cr_network_aspects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use imonroe\crps\Aspect;
use imonroe\crps\Ana;

class WebpageAspect extends Aspect{
	public function create_form($subject_id, $aspect_type_id=null){
		$form = \BootForm::open(['url' => '/aspect/create', 'method' => 'post', 'files' => false]);
		$form .= \BootForm::hidden('subject_id', $subject_id);
		$form .= \BootForm::hidden('aspect_type', $aspect_type_id );
		$form .= \BootForm::hidden('aspect_data');
		$form .= \BootForm::hidden('hidden', '0');
		$form .= \BootForm::hidden('file_upload');
		$form .= \BootForm::text('title', 'Title');
		$form .= \BootForm::text('aspect_source', 'URL');
		$form .= $this->notes_fields();
    $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
    $form .= \BootForm::close();
    return $form;
	}
	public function edit_form(){
		$current_aspect = Aspect::find($id);
		$form = \BootForm::open(['url' => '/aspect/'.$id.'/edit', 'method' => 'post', 'files' => false]);
		$form .= \BootForm::hidden('subject_id', $current_aspect->subjects()->first()->id);
		$form .= \BootForm::hidden('aspect_type', $current_aspect->aspect_type()->id );
		$form .= \BootForm::hidden('aspect_data', $current_aspect->aspect_data);
		$form .= \BootForm::hidden('hidden', $current_aspect->hidden);
		$form .= \BootForm::hidden('file_upload');
		$form .= \BootForm::text('title', 'Title', $current_aspect->title);
		$form .= \BootForm::text('aspect_source', 'URL', $current_aspect->aspect_source);
		$form .= $this->notes_fields();
    $form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
    $form .= \BootForm::close();
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
