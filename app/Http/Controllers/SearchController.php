<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use imonroe\crps\Aspect;
use imonroe\crps\AspectFactory;
use imonroe\crps\AspectType;
use imonroe\crps\Subject;
use App\Http\Controllers\GoogleController;

class SearchController extends Controller{

	/*
	*	The basic web search pulls results from DuckDuckGo to try to provide an abstract on a search subject.
	*/
	public function web_search($query){
		$duckduck_api_key = env('MASHAPE_API_KEY');
		$duckduck_api_endpoint = env('DUCKDUCK_API_ENDPOINT');
		$query_construction = $duckduck_api_endpoint.'?format=json&no_html=1&no_redirect=1&q='.urlencode($query).'&skip_disambig=1';
		$opts = array('Accept: application/json', 'X-Mashape-Key: '.$duckduck_api_key);
		$curl = curl_init();
		curl_setopt ($curl, CURLOPT_URL, $query_construction);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, 0);
		//curl_setopt($curl, CURLOPT_USERAGENT, $app['user-agent']);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $opts);
		$results = curl_exec($curl);
		curl_close ($curl);	
		$output = json_decode($results, true);
		return $output;
	}

	/*
	*	The controller function to get all the pieces and generate the view.
	*/
	public function show_search_results(Request $request){
		$query = $request->input('search_form_query');
		$web_search_results = $this->web_search( $query );
		$google_searcher = new GoogleController;
		$google_search_results = $google_searcher->google_search( $query );
		$google_drive_results = $google_searcher->search_drive( $query );
		$subject_results = $this->get_subject_results( $query);
		return view('search.results', ['abstract' => $web_search_results,
									   'google_search_results' => $google_search_results,
									   'google_drive_results' => $google_drive_results,
									   'subject_search_results' => $subject_results, 
									   'title'=>'Search Results for: '.$query,
									  ]);
	}

	public function get_subject_results($query){
		return Subject::where('name', 'LIKE', '%'.$query.'%')->get();
	}

}
