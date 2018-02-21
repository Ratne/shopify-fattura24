<?php defined( "BASEPATH" ) OR exit( "No direct script access allowed" );
header( "Access-Control-Allow-Origin: *" );
class Reviews extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		// $shop	= $this->input->post( "shop" );
		// $shop	= ( $shop )? $shop: $this->input->get( "shop" );
		// $this->shop = $shop;
		// $page	= $this->uri->segment(2);
		// $shop_details	= $this->shopapp_model->get_shop( $shop );
		// $login_page	= array( "home" );
		// if( empty( $shop_details->status ) && in_array( $page, $login_page ) )
		// {
			// redirect( site_uri( $this->home ) );
			// exit;
		// }
		// if( $shop_details )
		// {
			// $this->shop_details	= $shop_details;
			// $shop	= $shop_details->shop_name;
			// $access_token	= $shop_details->access_token;
		// }
		// $this->load->library( "shopifyclient" , array( "shop"  => $shop , "token" => ( empty( $access_token ) ? "" : $access_token ) , "key" => SHOPIFY_API_KEY , "secret" => SHOPIFY_SECRET ) );
		if( $this->session->flashdata( "success" ) )
		{
			$this->data["success"]	= $this->session->flashdata( "success" );
		}
	}
	public function index()
	{
		$this->output->set_content_type("application/json")->set_output( json_encode( $this->data ) ) ;
	}
	public function all()
	{
		$var = array() ;
		foreach( $this->input->get() as $key => $val )
		{
			$var[] = "$key=$val" ;
		}
		foreach( $this->input->post() as $key => $val )
		{
			$var[] = "$key=$val" ;
		}
		$json = file_get_contents( "https://api.feefo.com/api/10/importedreviews/all?" . implode( "&" , $var ) ) ;
		$this->data["data"] = json_decode( $json ) ;
		$this->data["reviews"] = $this->load->view( "reviews/all" , $this->data , true ) ;
		$this->index() ;
	}
	public function product_reviews()
	{
		$var = array() ;
		foreach( $this->input->get() as $key => $val )
		{
			$var[] = "$key=$val" ;
		}
		foreach( $this->input->post() as $key => $val )
		{
			$var[] = "$key=$val" ;
		}
		$json = file_get_contents( "https://api.feefo.com/api/10/importedreviews/product?" . implode( "&" , $var ) ) ;
		$this->data["data"] = json_decode( $json ) ;
		$this->index() ;
	}
}
?>