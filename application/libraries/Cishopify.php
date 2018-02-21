<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Set these values before you start!
define('SHOPIFY_API_KEY', 'df29824b683dc44cd1a2c7fc02495434');
define('SHOPIFY_API_SECRET', 'f0c9f295bfef184b6b4fcbd8ac5d517b');
define('SHOP_URL','http://profitpal.myshopify.com/');

define('FORMAT', 'xml');
define('GZIP_ENABLED', true); // set to false if you do not want gzip encoding. If false GZIP_PATH is not needed to be set
define('GZIP_PATH', '/tmp'); // path for gzip decoding (this file will need write permissions)
define('USE_SSL', false);

/* These values only need to be set if USE_SSL is true and the API cannot verify the certificate */
define('USE_SSL_PEM', false); //set to true if pem file is needed
define('CA_FILE', '/full/path/to/cacert.pem');

require_once('shopify_api.php');

/**
* Codeigniter wrapper around the shopify PHP API
*/
class Cishopify
{
	private $api = NULL;
	
	function __construct()
	{
		if(SHOPIFY_API_KEY == '' || SHOPIFY_API_SECRET == '' || SHOP_URL == '')
			show_error('You need to set your API key, API secret, and the shop URL in the library first');
		
		$this->ci =& get_instance();

		$this->ci->load->library('session');
		$this->ci->load->database();
		$this->api = $this->_getapi();
	}
	
	/**
	 * Authenticates the shop and fetches the token
	 *
	 * @return void
	 * @author Hasitha Pathiraja
	 */
	function authenticate()
	{
		$api = new Session(SHOP_URL, '', SHOPIFY_API_KEY, SHOPIFY_API_SECRET);
		redirect($api->create_permission_url());
	}
	
	/**
	 * returns a Session object fetched with the token to be used for API calls
	 *
	 * @return Session Session object fetched via the token
	 * @author Hasitha Pathiraja
	 */
	private function _getapi()
	{
		$shop = $this->ci->session->userdata('shopify_shop');
		$token = $this->ci->session->userdata('shopify_token');
		if(!$shop || !$token) $this->authenticate();
		return new Session($shop, $token, SHOPIFY_API_KEY, SHOPIFY_API_SECRET);
	}
	
	/**
	 * Sets the token and the shop in the session so that the rest of the library can use it to fetch data
	 *
	 * @param string $shop URL of the shop
	 * @param string $token Token that's returned
	 * @return void
	 * @author Hasitha Pathiraja
	 */
	function setapi($shop,$token)
	{
		$this->ci->session->set_userdata('shopify_shop',$shop);
		$this->ci->session->set_userdata('shopify_token',$token);
	}
	
	/*function printapi()
	{
		$this->api = $this->_getapi();
		prettyPrint($this->api);
	}*/
	
	/**
	 * Gets an array of products, optionally belonging to a particular collection
	 *
	 * @param string $collection_id The collection id (optional)
	 * @param array $params Array of additional parameters to pass in
	 * @param boolean $cache Whether the products should be loaded from cache or not
	 * @return array Collection of all the products matching the criteria. If no collection id is specified, all products will be returned
	 * @author Hasitha Pathiraja
	 */
	function getProducts($collection_id = 0, $params = array(), $cache = false)
	{
		return $this->api->product->get(0, $collection_id, $params, $cache);
	}
	
	/**
	 * Fetches a product by ID
	 *
	 * @param string $id ID of the product that's being fetched
	 * @param boolean $cache Whether the product should be loaded from cache or not
	 * @return array Product that matches the id. If no product is found, an array will be returned with an error message
	 * @author Hasitha Pathiraja
	 */
	function getProduct($id,$cache=false)
	{
		return $this->api->product->get($id, 0, array(), $cache);
	}
	
}
