<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/*
  Documenation : https://packagist.org/packages/francescomalatesta/wolframalphaphp

*/

class WolframAlphaController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public $api_endpoint;
	public $api_key;
	public $engine;

	public function __construct(){
		$this->api_endpoint = "http://api.wolframalpha.com/v2/query?input=pi&appid=XXXX";
		$this->api_key = env('WOLFRAM_ALPHA_APP_ID');
		$this->engine = new \WolframAlpha\Engine($this->api_key);
	}

	public function html_query($query){
		// process signature: public function process($query, $assumptions = array(), $formats = array('image', 'plaintext'))
		$output = '';
		$result_object = $this->engine->process($query, array(), array('image', 'json'));
		//$result = json_decode(json_encode($result_object), true);
		$pods = $result_object->pods;
		$result = json_decode(json_encode($pods), true);
		dd($pods);
		foreach ($result->pods as $pod){
			$output .= '<p>'.$pod->title.'</p>';
			$subpods = $pod->subpods;
			//dd($pod->subpods);
			foreach ($subpods as $subpod){
				dd('got in!');
				$img = $subpod->img;
				$output .= '<img src="'.$img->src.'" alt="'.$img->alt.'" title="'.$img->title.'" style="width:100%;" /><br />';
			}
		}
		dd($output);
		return $output;

	}


}