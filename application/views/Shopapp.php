<?php defined( "BASEPATH" ) OR exit( "No direct script access allowed" );
header( "Access-Control-Allow-Origin: *" );
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
	
	function ordercreate(){
		$this->load->model('Settings_model');	 
		header('Content-type: application/json');

		$Data = json_decode(file_get_contents('php://input'),true);
		/* $orderdetails =  $_REQUEST; */
		$orderid = $Data['order_number'];
		$vendor = $Data['line_items']['0']['vendor'];
		
			/* $var = '' ; */
		if(isset($Data)){
			/* foreach($Data as $key=>$val){
				$var .= $key.'=>'.$val.'<br/>' ; 
			} */
			$data = array(
				'order_id' => $orderid,
				'shop' => $vendor,
				'detail' => json_encode($Data,true)
			);
		
			//print_r($shop);  
		 $this->Settings_model->insertorder($data);	
		}	
	}
	public function uninstalled()
	{
		$shop_id	= isset( $this->shop_details->id )? $this->shop_details->id: 0;
		$this->shopapp_model->add_shop( array(  "status" => "0" ), $shop_id);
	}
	
	
	public function home()  
	{
		
		
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
}
?>