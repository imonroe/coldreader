<?php

/**
 * Coldreader 
 *
 * PHP version 5
 *
 * LICENSE: There's plenty of third-party libs in use, 
 * and nothing here should be interpreted to change or 
 * contradict anything that is stipulated in the licenses 
 * for those components.  As for my code, it's Creative 
 * Commons Attribution-NonCommercial-ShareAlike 3.0 
 * United States. (http://creativecommons.org/licenses/by-nc-sa/3.0/us/).  
 * For more information, contact Ian Monroe: ian@ianmonroe.com
 *
 * @author     Ian Monroe <ian@ianmonroe.com>
 * @copyright  2016
 * @version    0.1 ALPHA UNSTABLE
 * @link       http://www.ianmonroe.com
 * @since      File included in initial release
 *
 */
class WebScraper {
	public $url;
	public $data;
	public $aspect_type;
	public $subject_id;
	public function __construct() {
	}
	public function __destruct() {
	}
	public function scrape($url = '') {
		if ($url != '') {
			$this->url = $url;
		}
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 0 );
		$app = App::get_instance ();
		curl_setopt ( $curl, CURLOPT_USERAGENT, $app ['user-agent'] );
		$this->data = curl_exec ( $curl );
		curl_close ( $curl );
	}
	public function write_aspect() {
	}
}

?>