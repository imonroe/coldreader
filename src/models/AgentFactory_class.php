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
class AgentFactory {
	public static function create($agent_type = '') {
		switch ($agent_type) {
			case 'fullcontact' :
				$current_agent = new FullContactAgent ();
				return $current_agent;
				break;
			
			case 'wiki' :
				$current_agent = new WikiAgent ();
				return $current_agent;
				break;
			
			case 'aylien' :
				$current_agent = new AylienAgent ();
				return $current_agent;
				break;
			
			default :
				$current_agent = new Agent ();
				return $current_agent;
				break;
		}
	}
} // end AgentFactory class.

?>