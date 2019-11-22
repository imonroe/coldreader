<?php
namespace imonroe\cr_network_aspects\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use imonroe\crps\Aspect;
use imonroe\crps\AspectFactory;
use imonroe\crps\AspectType;
use imonroe\crps\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PicoFeed\Reader\Reader;


class RSSController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth');
  }

    public function parse_rss_aspect($aspect){
		dd(get_class($aspect));
		$feed_output = 'Nothing.';
		if ( get_class($aspect) == 'imonroe\cr_network_aspects\RSSFeedAspect' ){
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

	public function proxy_fetch_feed(Request $request){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //remove on upload
		curl_setopt($ch, CURLOPT_URL, $request->input('url'));
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$result = curl_exec($ch);
		echo curl_error($ch);
		curl_close($ch);
		return($result);
	}

}
