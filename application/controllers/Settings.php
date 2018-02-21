<?php defined( "BASEPATH" ) OR exit( "No direct script access allowed" );
header( "Access-Control-Allow-Origin: *" );
class Settings extends MY_Controller
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
		$this->load->model('Settings_model');
		if(isset($_GET['shop'])){
			$shop = $this->input->get("shop");
			$this->data["shop"]	= $shop ;
			 $shop_data = $this->Settings_model->get_shop($shop);
		}else{
			redirect( "shopapp" );
			exit; 
		}
		
		if (!empty($_POST)) 
				{
				
					$shop = $this->input->post('shop_name');
					$shop_data = $this->Settings_model->get_shop($shop);
					$shop_id = $shop_data->id;
					$api_key = $this->input->post('api_key');
					$send_email = $this->input->post('send_email');
					$id_templatea = $this->input->post('id_templatea');
					
					
							
							$data = array(
							     
								'api_key' => $api_key,
								'shop_id' => $shop_id,
								'send_email' => $send_email,
								'id_templatea' => $id_templatea
							);
						
							 $this->Settings_model->insert($data);	
						
				}
		
		
		
		$this->data["shop_setings"]	= $this->Settings_model->get_shop_settings($shop_data->id);	
		$this->data["home"]	= "shopapp";
		$this->data["active_tab"]	= "settings";
		$this->load->view("template", $this->data);
	}
	
	
		
		
		

}
?>