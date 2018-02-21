<?php

/**
 * HungryCityGuides
 *
 * @package		HungryCityGuides
 * @author		Sumeet Kashyap
 * @copyright	Copyright (c) 2011
 * @link		http://www.hungrycityguides.com
 * @since		Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Template Helper
 *
 * Provide helper functions for common template operations.
 *
 * @package		HungryCityGuides
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Sumeet Kashyap
 */


/** 
* Decode html content
* 
* @access public 
* @param string
* @return string
*/
function decode_html_content($content, $quote_style = ENT_QUOTES) {
	if(is_array($content)){
		return array_map('decode_html_content',$content, array_fill(0,count($content),$quote_style));
	}
	$trans_table = get_html_translation_table( HTML_SPECIALCHARS, $quote_style );
	if( $trans_table["'"] != '&#039;' ) {
		// some versions of PHP match single quotes to &#39;
		$trans_table["'"] = '&#039;';
	}
	return (strtr($content, array_flip($trans_table)));
	// Decode the html entities
	$content = stripslashes(htmlspecialchars_decode($content, ENT_QUOTES));

	return $content;
}

/** 
* Encode html content
* 
* @access public 
* @param string
* @return string
*/
function encode_html_content($content, $quote_style = ENT_QUOTES) {

	return is_array($content) ? array_map('encode_html_content',$content, array_fill(0,count($content),$quote_style)) : htmlspecialchars(htmlspecialchars_decode($content, $quote_style ),$quote_style);

}

function return_theme_path($adnl_path='') {

	$path = base_url();
	$path.='theme/';
	
	if(trim($adnl_path)!=''){
		$path.=$adnl_path.'/';
	}
	return $path;
}

function return_site_name(){
	
	$CI =& get_instance();
	
	$site_name = $CI->config->item('site_name','website_settings');
	
	return $site_name;
}

function return_copyright_text() {

	$CI =& get_instance();

	$copyright_text = $CI->config->item('site_copyright','website_settings');
	
	return $copyright_text;
}
function site_uri($url="")
{
	return site_url($url).($_SERVER['QUERY_STRING']? "?".$_SERVER['QUERY_STRING']: "");
}
function _array($array=array(), $key="")
{
	$arr = array();
	foreach ($array as $data)
	{
		if(is_object($data))
		{
			if(isset($data->$key))
			{
				$arr[] = $data->$key;
			}
		} elseif(is_array($data)) {
			if(isset($data[$key]))
			{
				$arr[] = $data[$key];
			}
		}
	}
	return $arr;
}
function printR( $array = array() )
{
	echo "<pre>" . print_r( $array , true ) . "</pre>" ;
}
/* End of file display_helper.php */ 
/* Location: ./application/helpers/display_helper.php */