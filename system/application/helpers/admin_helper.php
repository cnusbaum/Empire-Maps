<?php

/**
 * CBID Helper functions
 *
 * @package		admin
 * @category	Helper
 * @author		RIESTER Digital Team, Butch Clydesdale
 * @link		http://riester.com
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// TODO: adapt to json output
function get_google_map_coords($data) {
   $rval = null;
   //$base_url = 'http://maps.google.com/maps/geo?output=xml&key=' . GOOGLE_MAP_KEY;
   $base_url = 'http://maps.google.com/maps/geo?output=xml';

   if (!empty($data) && is_object($data)) {
      $address = $data->street1 . ',' .$data->city . ','. $data->state .' '.$data->zipcode;
      $request_url = $base_url . '&q=' . urlencode($address);

      $xml = simplexml_load_file($request_url);
      $status = $xml->Response->Status->code;
      if (strcmp($status, "200") == 0) {
         $coordinates = $xml->Response->Placemark->Point->coordinates;
         $coordinatesSplit = explode(",", $coordinates);
         $lat = $coordinatesSplit[1];
         $lng = $coordinatesSplit[0];
         $rval = array('lat' => $lat, 'lng' => $lng);
      }
   }
   return $rval;
} // get_google_map_coords


/**
 * Returns the passed address as a string into a single line
 *
 * @access	public
 * @param	$data object of the address to format
 * @return	string of the formatted address if successful, NULL otherwise
 */
function format_address_to_single_line($data) 
{
	$address = NULL;

	if (!empty($data) && is_object($data)) {
		if (!empty($data->street1)) {
			$address .= $data->street1 . ', ';
		}
		
		//if (!empty($data->street2)) {
		//	$address .= $data->street2 . ', ';
		//}
		
		if (!empty($data->city)) {
			$address .= $data->city . ', ';
		}
		
		if (!empty($data->state)) {
			$address .= $data->state . ' ';
		}
		
		if (!empty($data->zipcode)) {
			$address .= $data->zipcode;
		}
	
	}
	return $address;
} // format_address_to_single_line


/**
 * Checks if the user's session contains their User ID (indicating authenticated)
 * for the user sesson.
 *
 * @access	public
 * @param   $data and array of data from database
 * @return  an array of data if successful, otherwise NULL
 */
function is_logged_in() {
	return $this->session->userdata('user_id');
} // is_logged_in



/**
 * Create a random tokenized string used for a unique identifier for login, this
 * also gets written to a client cookie
 *
 * @access	public
 * @param   none
 * @return  a random string used for a token
 */
function create_login_token() {
	return substr(md5(rand(0,9999999)),0,20);
} // create_login_token



/**
 * Sets the expiration amount for the client cookie on the website.
 * This is currently set to 90 days.
 *
 * @access	public
 * @param   none
 * @return  a time in days to expire cookie
 */
function get_cookie_expiration() {
	return time()+60*60*24*90;
} // set_cookie_expiration



/**
 * Writes a cookie to the user's client machine, if an array of data is passed into
 * function, if uses that to write to cookie, otherwise it write default data.
 *
 * @access	public
 * @param   $token the token to save in cookie, optional 
 * @return  an array of data to written to cookie
 */
function create_client_token_cookie($token = NULL) {
	$CI =& get_instance();
	$CI->load->helper('cookie');
	$token = ($token) ? $token : create_login_token();
	$data = array(
		'name'   => 'token',
		'value'  => $token,
		'expire' => get_cookie_expiration(),
		//'domain' => '.winecoastcountry.com',
		//'domain'  => 'cbid.local',
		//'path'   => '/',
		'prefix' => 'cbid_' );
	set_cookie($data);
	return $data;
} // set_client_cookie



/**
 * Formats the passed array to a javascript array to feed to the Google
 * Map to set markers.
 *
 * @access	public
 * @param   $data an array of geo locations to format
 * @return  a string of formatted data
 */
function format_geocodes_for_map($data) {
	$rval = NULL;
	if (!empty($data) && is_array($data)) {
		$t = array();		
		foreach($data as $d) {
			if (!empty($d->address)) {
				array_push($t, " [".$d->address->latitude . "," . $d->address->longitude . "] ");
			}
		}
		//$rval = " [ " .  implode(',', $t)  . " ] ";
		$rval = implode(',', $t) ;
	}
	return $rval;
} // format_geocodes_for_map



/**
 * Slugify the text
 *
 * @param   $text
 * @return  clean text
 */
function slugify($str, $delimiter='-'){
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}



/**
 * Creates the anchor tag for the results table header to allow column sorting.
 * If the controller is global then pass in <code>TRUE</code> to remove the
 * session vertical from the controller path. 
 *
 * @param  $column
 * @param  $label
 * @param  $sort
 * @param  $sort_order
 * @param  $offset
 * @param  $global
 * @return string
 */
function create_anchor($column, $label, $sort, $sort_order, $offset, $per_page)
{
    $ci =& get_instance();

    $controller_path = sprintf('admin/%s/%s', $ci->router->class, $ci->router->method);

    return anchor( sprintf('/%s/%s/%s/%s/%s', $controller_path, $column, get_sort($column, $sort, $sort_order), $per_page, $offset), $label.create_sort_image($column, $sort, $sort_order));
}

/**
 * displays the correct image based on the sort order
 *
 * @param  $column
 * @param  $sort
 * @param  $sort_order
 * @return string
 */
function create_sort_image($column, $sort, $sort_order)
{
    return '<span class="ui-icon '.($sort == $column ? ($sort_order == 'asc' ? 'ui-icon-triangle-1-s' : 'ui-icon-triangle-1-n') : 'ui-icon-triangle-2-n-s').'"></span>';
}

/**
 * sets the correct sort for the link when clicked
 *
 * @param  $column
 * @param  $sort
 * @param  $sort_order
 * @return string
 */
function get_sort($column, $sort, $sort_order)
{
    return ($sort == $column ? ($sort_order == 'asc' ? 'desc' : 'asc') : 'asc');
}



/* End of file cbid.php */
/* Location: ./system/application/helpers/cbid.php */

