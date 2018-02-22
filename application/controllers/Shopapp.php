<?php defined( "BASEPATH" ) OR exit( "No direct script access allowed" );
header( "Access-Control-Allow-Origin: *" );

require (__DIR__) . '../../vendor/autoload.php';

use \Curl\Curl;

class Shopapp extends MY_Controller
{
public function __construct()
{
parent::__construct();

$shop	= $this->input->post( "shop" );
$shop	= ( $shop )? $shop: $this->input->get( "shop" );
$this->shop = $shop;
$page	= $this->uri->segment(2);
$shop_details	= $this->shopapp_model->get_shop( $shop );
$login_page	= array( "home" );
if( empty( $shop_details->status ) && in_array( $page, $login_page ) )
{
redirect( site_uri( "shopapp" ) );
exit;
}
if( $shop_details )
{
$this->shop_details	= $shop_details;
$shop	= $shop_details->shop_name;
$access_token	= $shop_details->access_token;
}
$this->load->library( "shopifyclient" , array( "shop"  => $shop , "token" => ( empty( $access_token ) ? "" : $access_token ) , "key" => SHOPIFY_API_KEY , "secret" => SHOPIFY_SECRET ) );
if( $this->session->flashdata( "success" ) )
{
$this->data["success"]	= $this->session->flashdata( "success" );
}
}
public function index()
{
$shop = $this->shop;
if( !empty( $shop ) )
{
if( preg_match( "/^[a-z0-9\.\-\_]+[.]+[a-z]{2,5}$/" , $shop ) )
{
$this->install() ;
} else {
$this->session->set_flashdata( "shop_error" , "<p class='error'>Please enter valid shop name</p>" );
redirect( "shopapp" );
exit;
}
}




$this->load->view("index", $this->data);
}
public function install()
{
$location	= $this->shopifyclient->getAuthorizeUrl( SHOPIFY_SCOPE , site_url( "shopapp/response" ) );
header( "location: $location" );
exit;
}
public function response()
{

// die('here');
$code = $this->input->get("code");
$hmac = $this->input->get("hmac");
$shop = $this->input->get("shop");
$timestamp = $this->input->get("timestamp");
$signature = $this->input->get("signature");
$access_token	= $this->shopifyclient->getAccessToken($code);
if(!empty($access_token))
{
$shop_id	= isset( $this->shop_details->id )? $this->shop_details->id: 0;
$this->shopapp_model->add_shop(array( "shop_name" => $shop , "status" => "1" , "access_token" => $access_token ), $shop_id);
}
redirect( site_url( "shopapp/installed?hmac=$hmac&shop=$shop&signature=$signature&timestamp=$timestamp" ) );
exit;
}
public function installed()
{
$webhooks	= $this->shopifyclient->call("GET", "/admin/webhooks.json");

/* echo "<pre>" ; 
print_R($webhooks); */

foreach( $webhooks as $webhook )
{
if( $webhook["topic"] == "app/uninstalled" )
{
$webhook_id	= $webhook["id"];
}
if( $webhook["topic"] == "orders/create" )
{
$ordercreate	= $webhook["id"];
}
}
if( empty( $webhook_id ) )
{
$this->shopifyclient->call( 'POST', "/admin/webhooks.json", array( "webhook" => array( "topic" => "app/uninstalled", "address" => site_uri("shopapp/uninstalled"), "format" => "json" ) ) );

} else {
$this->shopifyclient->call( 'PUT', "/admin/webhooks/$webhook_id.json", array( "webhook" => array( "topic" => "app/uninstalled", "address" => site_uri("shopapp/uninstalled"), "format" => "json" ) ) );
}
if( empty( $ordercreate ) )
{
$this->shopifyclient->call( 'POST', "/admin/webhooks.json", array( "webhook" => array( "topic" => "orders/create", "address" => site_uri("shopapp/ordercreate"), "format" => "json" ) ) );

} else {
$this->shopifyclient->call( 'PUT', "/admin/webhooks/$ordercreate.json", array( "webhook" => array( "topic" => "orders/create", "address" => site_uri("shopapp/ordercreate"), "format" => "json" ) ) );
}



/*  die; */


redirect( site_uri( "shopapp/home" ) ); 
exit;
}
function checkdetails(){

	$this->load->model('Settings_model');	
	$vendor = $vendor;
	$shop_name = $vendor.".myshopify.com";

	$getshop_details = $this->Settings_model->getshop_details($shop_name);
	echo $getshop_details->api_key ; 
	echo "<br>" ;
	echo $getshop_details->send_email ; 
	echo "<br>" ;
	echo $getshop_details->id_templatea ; 
	echo "<br>" ;
	/* die; */
}
function ordercreate(){
	
	
	
	 $this->load->model('Settings_model');	 
	header('Content-type: application/json');
	$Order = json_decode(file_get_contents('php://input'),true);
	$orderid = $Order['order_number'];
	$vendor = $Order['line_items']['0']['vendor'];	
	if(isset($Order)){
		$order = array(
		'order_id' => $orderid,
		'shop' => $vendor,
		'detail' => json_encode($Order,true)
	);
	$this->Settings_model->insertorder($order);
	
	//$vendor = "sunnybhonka";
	 $vendor = $vendor;
	$shop_name = $vendor.".myshopify.com";		 
	$getshop_details = $this->Settings_model->getshop_details($shop_name);
	
	if($getshop_details->send_email == 1){
		$SendEmail = 'true';
	}else {
		$SendEmail = 'false';
	}
	 $template = $getshop_details->id_template ;
	
		 		
		if ($Order['shipping_address']['company']!="")
		{
			$uname = $Order['shipping_address']['company'];  	
		}
		{
			$uname = $Order['shipping_address']['first_name'].' '.$Order['shipping_address']['last_name'];  
		}
		
		
		$email = $Order['email'];
		$orderno = $Order['id'];
		$address = $Order['shipping_address']['address1'];
		$zip = $Order['shipping_address']['zip'];
		$city = $Order['shipping_address']['city'];
		$country = $Order['shipping_address']['country'];
		$province = $Order['shipping_address']['province'];
		$phone = $Order['shipping_lines']['phone'];   

		$gateway = $Order['gateway'];
		$subtotal_price = $Order['subtotal_price'];
		$total_tax = $Order['total_tax'];
		$total_price = $Order['total_price'];

		$cf=$Order['shipping_address']['address2'];
		$piva=$Order['shipping_address']['address2'];
		
		if (is_numeric($piva))
		{
			$cf="";
		}
		else
		{
			$piva="";
		}
		
 
		$api_key= $getshop_details->api_key; 
		
	$fattura24_api_url = "https://www.app.fattura24.com/api/v0.3/TestKey" ; 
	$send_data = array();
    $send_data['apiKey'] = $api_key;
    $response =  $this->curlDownload($fattura24_api_url, http_build_query($send_data));
	
	
	if(strpos($response,'&egrave;')){
		$response = str_replace('&egrave;','',$response);
	}
	$xml=simplexml_load_string($response) or die("Error: Cannot create object");
	if ($xml->returnCode==1)
	{
		// the customer data, reading it from the json
		$customerData = array(
			'Name'      => $uname,
			'Email'     => $email,
			'FiscalCode'      => $cf,
			'VatCode'      => $piva,
			'Address'      => $address,
			'Postcode'      => $zip,
			'City'      => $city,
			'Country'      => $country,		
			'Province'      => $province,	
			'CellPhone'		=> $phone, 
		);   	  
	// we create an xml in order to create or update the customer
	$xml = new \XMLWriter();
	$filename = uniqid().".xml";
	$xml->openURI(__DIR__ .$filename);       

	$xml->startDocument('1.0', 'UTF-8');
	$xml->setIndent(2);
	$xml->startElement('Fattura24');
	$xml->startElement('Document');
	// we add the customer data into the xml
	foreach($customerData as $k => $v)
	{
		$xml->writeElement('Customer'.$k, $v);
	}			
	$xml->endElement();
	$xml->endElement();
	$xml->endDocument();
	$xml->flush();
	 $data=file_get_contents(__DIR__ .$filename);   
	 
	$curl = new Curl();
	$curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
	$curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);		
	$curl->post('https://www.app.fattura24.com/api/v0.3/SaveCustomer', array(
		'apiKey' => $api_key, 
		'xml' => $data
	));		
	 
	
	
	
	
	// we delete the temporary file
	$percorso=__DIR__ .$filename;
	unlink($percorso);	  
	$xml = new \XMLWriter();
	$filename_doc=uniqid().".xml";
	$xml->openURI(__DIR__ .$filename_doc);

	$xml->startDocument('1.0', 'UTF-8');
	$xml->setIndent(2);
	$xml->startElement('Fattura24');

	$xml->startElement('Document');

	$xml->writeElement('DocumentType','I-force');
	$xml->writeElement('SendEmail', $SendEmail);	// the value from the shopify app
	$xml->writeElement('IdTemplate', $template);		// the value from the shopify app
	$xml->writeElement('Object', $orderno);
// we add the customer data into the invoice
	foreach($customerData as $k => $v)
	{
		$xml->writeElement('Customer'.$k, $v);	
	}
		// here we add the totals from the json
		$xml->writeElement('PaymentMethodName', $gateway);
		$xml->writeElement('PaymentMethodDescription', 'custom_transation_id');

		$xml->writeElement('TotalWithoutTax', number_format($subtotal_price,2,".",""));
		$xml->writeElement('VatAmount', number_format($total_tax,2,".",""));
		$xml->writeElement('Total', number_format($total_price,2,".",""));		

		$xml->startElement('Payments');
		$xml->startElement('Payment');
		$xml->writeElement('Date', date('Y-m-d'));

		$xml->writeElement('Amount',number_format($total_price,2,".",""));		
		$xml->writeElement('Paid', 'true');
		$xml->endElement();
		$xml->endElement();


		$xml->startElement('Rows');

		//for each json line_items row, we add a row into the invoice with the correct data from json
		
		foreach($Order['line_items'] as $oitem){
			$xml->startElement('Row');
		$xml->writeElement('Description', $oitem['title']);

		$xml->writeElement('Qty', $oitem['quantity']);
		$xml->writeElement('Price', number_format($oitem['price'],2,".",""));
		$xml->writeElement('VatCode', 22);
		$xml->writeElement('Code', '');			

		$xml->endElement();
			
		}
		
		
		

		$xml->endElement();
		$xml->endElement();
		$xml->endDocument();
		$xml->flush();	
		$data=file_get_contents(__DIR__ .$filename_doc);   	

	
	
	$curl = new Curl();
	$curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
	$curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);		
	$curl->post('https://www.app.fattura24.com/api/v0.3/SaveDocument', array(
		'apiKey' => $api_key, 
		'xml' => $data
	));		
	 
	
	
	
	
	
	
	$percorso_doc=__DIR__ .$filename_doc;
unlink($percorso_doc);	   

	} 
	}
}	

	function uninstalled()
	{
		$shop_id	= isset( $this->shop_details->id )? $this->shop_details->id: 0;
		$this->shopapp_model->add_shop( array(  "status" => "0" ), $shop_id);
	}


	function home() {
		if(isset($_GET['shop'])){
			$shop = $this->input->post("shop");
			$this->data["shop"]	= $shop ;
		}else{
			redirect( "shopapp" );
			exit;
		}
		$this->data["view"]	= "home";
		$this->data["home"]	= "home";
		$this->data["active_tab"]	= "home";
		$this->load->view("template", $this->data);
	}
	
	
	
function curlDownload($url, $data_string)
{
    if (!function_exists('curl_init'))
    {
        $this->trace('curl is not installed'); // metodo di log
        die('Sorry, cURL is not installed!');
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    $config = array();
    $config['useragent'] = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
    curl_setopt($ch, CURLOPT_USERAGENT, $config['useragent']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $output = curl_exec($ch);
    if(curl_errno($ch) != 0)
        $this->trace('curl error', implode("\n", curl_getinfo($ch)), curl_error($ch), curl_errno($ch));
    curl_close($ch);
    return $output;
}
function apiCall($fattura24_api_url, $apiKey)
{
    $send_data = array();
    $send_data['apiKey'] = $apiKey;
    return $this->curlDownload($fattura24_api_url, http_build_query($send_data));
}
	
}
?>