<?php

namespace imonroe\cr_network_aspects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use imonroe\crps\Aspect;
use imonroe\crps\Ana;
use \Goutte\Client;

class WebScraperAspect extends \App\LamdaFunctionAspect{
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
		$form = \BootForm::open(['url' => '/aspect/create', 'method' => 'post', 'files' => true]);
		$form .= \BootForm::hidden('subject_id', $subject_id);
		$form .= \BootForm::hidden('aspect_type', $aspect_type_id );
		$form .= \BootForm::text('title', 'Title');
		$form .= \BootForm::hidden('aspect_source', null);
		$form .= \BootForm::hidden('aspect_data');
		$form .= \BootForm::hidden('hidden', '1');
		$form .= \BootForm::hidden('file_upload');
		$form .= $this->notes_fields();
		$form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
		$form .= \BootForm::close();
		$output .= $form;
        return $output;
	}
	public function edit_form(){
		$output = $this->display_aspect() . '<hr />';
		$form = \BootForm::open(['url' => '/aspect/'.$this->id.'/edit', 'method' => 'post', 'files' => false]);
		$form .= \BootForm::hidden('subject_id', $this->subject_id);
		$form .= \BootForm::hidden('aspect_type', $this->aspect_type );
		$form .= \BootForm::text('title', 'Title', $this->title);
		$form .= \BootForm::hidden('aspect_source',$this->aspect_source);
		$form .= \BootForm::hidden('aspect_data', $this->aspect_data);
		$form .= \BootForm::hidden('hidden', $this->hidden);
		$form .= \BootForm::hidden('file_upload');
		$form .= $this->notes_fields();
		$form .= \BootForm::submit('Submit', ['class' => 'btn btn-primary']);
		$form .= \BootForm::close();
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
    try{
      $element_array = $crawler->filter($settings['DOM_selector'])->children();
    } catch (\Exception $e){
      // we need an empty array to continue.
      $element_array = array();
      $output .= 'Some kind of error happened: '.$e->getMessage();
    }
    foreach ($element_array as $domElement){
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
