<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PicoFeed\Reader\Reader;
use imonroe\crps\Subject;
use imonroe\crps\Aspect;

class RSSController extends Controller
{
    public function parse_rss_aspect($aspect){
		(get_class($aspect));
		$feed_output = 'Nothing.';
		if ( get_class($aspect) == 'App\RSSFeedAspect' ){
			$requested_feed = trim($aspect->aspect_source);
			try {
				$reader = new Reader;
				$resource = $reader->download($requested_feed);
				$parser = $reader->getParser(
					$resource->getUrl(),
					$resource->getContent(),
					$resource->getEncoding()
				);
				$feed_output = $parser->execute();
			} catch (\Exception $e) {
				Log::info('Exception trying to parse a feed: '.$e);
				$feed_output = 'Error in feed output';
			}
		}
		return $feed_output;
	}
	
	public function get_feed_via_ajax($aspect_id){
		$feed_aspect = Aspect::find($aspect_id);
		return $this->parse_rss_aspect($feed_aspect);
	}

	public function generate_news_page($subject_id=4){
		$news_page_subject = Subject::find($subject_id);

		return view('news.index', ['news_aspects' => $news_page_subject]);
	}

}
