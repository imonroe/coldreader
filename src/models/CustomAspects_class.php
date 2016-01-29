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

// default custom class created automatically.
class APIResultAspect extends Aspect {
	public function display_aspect() {
		$output = "<strong>API Result: <strong>";
		$output .= '<pre>';
		$output .= $this->aspect_data;
		$output .= '</pre>';
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class InspirationalPhraseAspect extends Aspect {
	public function display_aspect() {
		if (! $this->is_hidden) {
			if ($this->markdown = 1) {
				$output_data = '';
				$parser = new Parsedown ();
				$output_data = $parser->text ( $this->aspect_data );
			} else {
				$output_data = $this->aspect_data;
			}
			$output = '<div id="aspect_' . $this->id . '">';
			$output .= '<p><strong>' . $this->return_aspect_type_name () . ': </strong>' . $this->aspect_data;
			$output .= ' <span class="small">(<a href="index.php?p=form_edit_aspect&id=' . $this->id . '">Edit</a>)</span>';
			$output .= '</p>';
			$output .= '</div>';
			return $output;
		} else {
			return;
		}
	}
	public function parse() {
	}
}

// default custom class created automatically.
class GoogleContactsAPIResultAspect extends Aspect {
	public function display_aspect() {
		// $output = parent::display_aspect();
		$contact_array = unserialize ( $this->aspect_data );
		$output = '<div><pre> ' . print_r ( $contact_array, true ) . ' </pre></div>';
		return $output;
	}
	public function parse() {
		// we're going to update the aspect with new data from Google.
		new LogEntry ( 'Parsing Google Contacts aspect.' );
		$app = App::get_instance ();
		$client = $app ['google'] ['client'];
		$resp = json_decode ( $_SESSION ['access_token'] );
		$accesstoken = $resp->access_token;
		$req = new Google_Http_Request ( 'https://www.google.com/m8/feeds/contacts/default/full?v=3.0&max-results=2000&alt=json' );
		$val = $client->getAuth ()->authenticatedRequest ( $req );
		$response = $val->getResponseBody ();
		$this->aspect_data = serialize ( json_decode ( $response, true ) );
		$this->update ();
		$this->add_new_people ();
	}
	public function add_new_people() {
		$raw_array = unserialize ( $this->aspect_data );
		$entry_array = $raw_array ['feed'] ['entry'];
		foreach ( $entry_array as $entry ) {
			// first, check to see if the entry already exists by looking for a google Contact Id.
			if ((! find_aspect_from_data ( $entry ['id'] ['$t'] )) && (! empty ( $entry ['title'] ['$t'] ))) {
				// no entry exists, create a new subject and populate.
				$person = new Subject ();
				$person->subject_type_id = '14'; // hardcoded for the subject type at the moment. This should be replaced with a factory.
				$person->name = $entry ['title'] ['$t'];
				$person->save (); // make sure we save it.
				                 // now let's add aspects.
				if (isset ( $entry ['id'] ['$t'] )) {
					$person->quick_add ( 'Google Contact ID', $entry ['id'] ['$t'] );
				}
				// ////////
				if (isset ( $entry ['gd$etag'] )) {
					$person->quick_add ( 'Google Contact Etag', $entry ['gd$etag'] );
				}
				// ///////
				if (isset ( $entry ['gd$name'] ['gd$givenName'] ['$t'] )) {
					$person->quick_add ( 'First Name', $entry ['gd$name'] ['gd$givenName'] ['$t'] );
				}
				// ///////
				if (isset ( $entry ['gd$name'] ['gd$familyName'] ['$t'] )) {
					$person->quick_add ( 'Last Name', $entry ['gd$name'] ['gd$familyName'] ['$t'] );
				}
				// ///////
				if (isset ( $entry ['gContact$gender'] ['value'] )) {
					$person->quick_add ( 'Gender', $entry ['gContact$gender'] ['value'] );
				}
				// ///////
				if (isset ( $entry ['gd$organization'] )) {
					// loop through.
					foreach ( $entry ['gd$organization'] as $organizatiom ) {
						// this requires more thought.
						// $person->quick_add('Gender', $entry['gContact$gender']['value']);
					}
				}
				// ///////
				if (isset ( $entry ['gd$email'] )) {
					foreach ( $entry ['gd$email'] as $email ) {
						$person->quick_add ( 'Email Address', $email ['address'] );
					}
				}
				// ///////
				if (isset ( $entry ['gd$phoneNumber'] )) {
					foreach ( $entry ['gd$phoneNumber'] as $phone ) {
						$person->quick_add ( 'Telephone Number', $phone ['uri'] );
					}
				}
				if (isset ( $entry ['gd$structuredPostalAddress'] )) {
					if (isset ( $entry ['gd$structuredPostalAddress'] [0] ['gd$street'] ['$t'] )) {
						$person->quick_add ( 'Street Address', $entry ['gd$structuredPostalAddress'] [0] ['gd$street'] ['$t'] );
					}
					if (isset ( $entry ['gd$structuredPostalAddress'] [0] ['gd$city'] ['$t'] )) {
						$person->quick_add ( 'City', $entry ['gd$structuredPostalAddress'] [0] ['gd$city'] ['$t'] );
					}
					if (isset ( $entry ['gd$structuredPostalAddress'] [0] ['gd$region'] ['$t'] )) {
						$person->quick_add ( 'State', $entry ['gd$structuredPostalAddress'] [0] ['gd$region'] ['$t'] );
					}
					if (isset ( $entry ['gd$structuredPostalAddress'] [0] ['gd$postcode'] ['$t'] )) {
						$person->quick_add ( 'Zip Code', $entry ['gd$structuredPostalAddress'] [0] ['gd$postcode'] ['$t'] );
					}
					if (isset ( $entry ['gd$structuredPostalAddress'] [0] ['gd$formattedAddress'] ['$t'] )) {
						$person->quick_add ( 'Formatted Address', $entry ['gd$structuredPostalAddress'] [0] ['gd$formattedAddress'] ['$t'] );
					}
				}
				
				if (isset ( $entry ['gContact$website'] )) {
					foreach ( $entry ['gContact$website'] as $link ) {
						$link_html = '<a href="' . $link ['href'] . '">' . $link ['label'] . '</a>';
						$person->quick_add ( 'Hyperlink', $link_html );
					}
				}
				
				$person->update ();
			} // end duplicate check.
		} // end foreach
	} // end add_new_people function
}

// default custom class created automatically.
class GoogleContactIDAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class GoogleContactEtagAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class FullNameAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class FirstNameAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class LastNameAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class GenderAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class OrganizationNameAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class OrganizationTitleAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class EmailAddressAspect extends Aspect {
	public function display_aspect() {
		$output = '';
		if (! $this->is_hidden) {
			if ($this->markdown = 1) {
				$output_data = '';
				$parser = new Parsedown ();
				$output_data = $parser->text ( $this->aspect_data );
			} else {
				$output_data = $this->aspect_data;
			}
			$output = '<div id="aspect_' . $this->id . '">';
			$output .= '<p>Email: <a href="mailto:' . $this->aspect_data . '">' . $this->aspect_data . '</a>';
			$output .= ' <span class="small">(<a href="index.php?p=form_edit_aspect&id=' . $this->id . '">Edit</a>)</span>';
			$output .= '</p>';
			$output .= '</div>';
			return $output;
		} else {
			// do nothing because it's hidden
		}
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class TelephoneNumberAspect extends Aspect {
	public function display_aspect() {
		$output = '';
		if (! $this->is_hidden) {
			if ($this->markdown = 1) {
				$output_data = '';
				$parser = new Parsedown ();
				$output_data = $parser->text ( $this->aspect_data );
			} else {
				$output_data = $this->aspect_data;
			}
			$output = '<div id="aspect_' . $this->id . '">';
			$output .= '<p>Phone: <a href="' . $this->aspect_data . '">' . $this->aspect_data . '</a>';
			$output .= ' <span class="small">(<a href="index.php?p=form_edit_aspect&id=' . $this->id . '">Edit</a>)</span>';
			$output .= '</p>';
			$output .= '</div>';
			return $output;
		} else {
			// do nothing because it's hidden
		}
		
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class StreetAddressAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		
		return $output;
	}
	public function parse() {
		$my_subject = new Subject ( $this->return_subject_id () );
		if (! $my_subject->has_aspect_named ( 'Formatted Address' )) {
			$city = $my_subject->has_aspect_named ( 'city' ); // should give us the id, if it exists.
			if ($city) {
				$city_aspect = new Aspect ( $city );
				$structured_address = $this->aspect_data;
				$structured_address .= ' ' . $city_aspect->aspect_data;
				$my_subject->quick_add ( 'Formatted Address', $structured_address );
				new LogEntry ( 'parsed a new formatted address for subject:' . $my_subject->id );
			}
		}
	}
}

// default custom class created automatically.
class CityAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class StateAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class ZipCodeAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class FormattedAddressAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		$app = App::get_instance ();
		$output .= '<hr>';
		$output .= '<iframe width="600" height="400" frameborder="0" style="border:0px;" ';
		$output .= 'src="https://www.google.com/maps/embed/v1/place?';
		$output .= 'key=' . $app ['google'] ['maps'] ['api_key'];
		$output .= '&q=' . str_replace ( " ", '+', $this->aspect_data ) . '" ';
		$output .= 'allowfullscreen></iframe>';
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class HyperlinkAspect extends Aspect {
	public function display_aspect() {
		$output = '';
		
		if (! $this->is_hidden) {
			if ($this->markdown = 1) {
				$output_data = '';
				$parser = new Parsedown ();
				$output_data = $parser->text ( $this->aspect_data );
			} else {
				$output_data = $this->aspect_data;
			}
			$output = '<div id="aspect_' . $this->id . '">';
			$output .= '<p>' . $this->aspect_data;
			$output .= ' <span class="small">(<a href="index.php?p=form_edit_aspect&id=' . $this->id . '">Edit</a>)</span>';
			$output .= '</p>';
			$output .= '</div>';
			return $output;
		} else {
			// do nothing because it's hidden
		}
		
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class AvatarPhotoAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class ProjectDescriptionAspect extends Aspect {
	public function display_aspect() {
		if (! $this->is_hidden) {
			if ($this->markdown = 1) {
				$output_data = '';
				$parser = new Parsedown ();
				$output_data = $parser->text ( $this->aspect_data );
			} else {
				$output_data = $this->aspect_data;
			}
			$output = '<div id="aspect_' . $this->id . '">';
			$output .= '<h5><strong>' . $this->return_aspect_type_name () . ', ' . $this->update_date . ': </strong></h5>' . $output_data;
			$output .= ' <span class="small">(<a href="index.php?p=form_edit_aspect&id=' . $this->id . '">Edit</a>)</span>';
			$output .= '</p>';
			$output .= '</div>';
			return $output;
		} else {
			return;
		}
	}
	public function parse() {
	}
}

// default custom class created automatically.
class ProjectStatusAspect extends Aspect {
	public function display_aspect() {
		$output = '<div id="aspect_' . $this->id . '">';
		$output .= '<span class="aspect_label">' . $this->return_aspect_type_name () . ', ' . $this->update_date . ': </span><strong>' . $this->aspect_data;
		$output .= '</strong> <span class="small">(<a href="index.php?p=form_edit_aspect&id=' . $this->id . '">Edit</a>)</span>';
		$output .= '</p>';
		$output .= '</div>';
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class UpdateAspect extends Aspect {
	public function display_aspect() {
		if (! $this->is_hidden) {
			if ($this->markdown = 1) {
				$output_data = '';
				$parser = new Parsedown ();
				$output_data = $parser->text ( $this->aspect_data );
				// $output_data = $this->aspect_data;
			} else {
				$output_data = $this->aspect_data;
			}
			$output = '<div id="aspect_' . $this->id . '">';
			$output .= '<h5><strong>' . $this->return_aspect_type_name () . ', ' . $this->create_date . ': </strong></h5>' . $output_data;
			$output .= ' <span class="small">(<a href="index.php?p=form_edit_aspect&id=' . $this->id . '">Edit</a>)</span>';
			$output .= '</p>';
			$output .= '</div>';
			return $output;
		} else {
			return;
		}
	}
	public function parse() {
	}
}

// default custom class created automatically.
class WebClippingAspect extends Aspect {
	public function the_gist() {
		$app = App::get_instance ();
		$textapi = new AYLIEN\TextAPI ( $app ['aylien'] ['app_id'], $app ['aylien'] ['api_key'] );
		$extract = $textapi->Extract ( array (
				'url' => $this->aspect_source,
				'best_image' => 'true' 
		) );
		// var_dump($extract);
		$extract_json = json_encode ( $extract );
		return $extract_json;
	}
	public function display_aspect() {
		// $output = parent::display_aspect();
		$output = '<div id="aspect_' . $this->id . '">';
		$output .= '<strong><h5>Web Clipping</h5> Source:</strong> <a href="' . $this->aspect_source . '" target="_blank">' . $this->aspect_source . '</a> on ' . $this->create_date;
		if (! empty ( $this->aspect_notes )) {
			$summary_object = json_decode ( $this->aspect_notes );
			$output .= "<br />";
			$parser = new Parsedown ();
			$output .= $parser->text ( $summary_object->article );
		}
		$output .= ' <span class="small">(<a href="index.php?p=form_edit_aspect&id=' . $this->id . '">Edit</a>)</span>';
		$output .= '</div>';
		// $output = "We are in the correct web clipping aspect.";
		return $output;
	}
	public function parse() {
		$app = App::get_instance ();
		if (empty ( $this->aspect_data ) && ! empty ( $this->aspect_source )) {
			if ($app ['ana']->is_valid_link ( $this->aspect_source )) {
				$fetcher = new Snoopy ();
				$fetcher->fetch ( $this->aspect_source );
				$this->aspect_data = $fetcher->results;
				$this->update ();
			} else {
				new LogEntry ( 'Web clipping not a valid link: ' . $this->aspect_source );
			}
		} else {
			new LogEntry ( 'web clipping appears to be empty already.' );
		}
		
		if (! empty ( $this->aspect_data ) && empty ( $this->aspect_notes )) {
			$this->aspect_notes = $this->the_gist ();
			$this->update ();
		}
	}
}

// default custom class created automatically.
class TitleAspect extends Aspect {
	public function display_aspect() {
		$output = parent::display_aspect ();
		return $output;
	}
	public function parse() {
	}
}

// default custom class created automatically.
class ImageAspect extends Aspect {
	public function display_aspect() {
		$app = App::get_instance ();
		$output = parent::display_aspect ();
		
		$output .= '<div class="image_aspect" id="id_' . $this->id . '" ><img src ="' . $app ['upload_src_path'] . $this->aspect_data . '" style="max-width:100%;" /></div>';
		return $output;
	}
	public function parse() {
	}
}

// end file

?>