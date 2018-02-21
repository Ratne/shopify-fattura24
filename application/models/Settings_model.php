<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model {


    public function insert($data)
	{
		
		$shop_data = $this->get_shop_settings($data['shop_id']) ; 
		
		if(!empty($shop_data)){
			 $this->db->where('shop_id', $data['shop_id']);
			 $this->db->update('fattura_shop_setting', $data);
			 
		}else{
			 $this->db->insert('fattura_shop_setting', $data);
			
		}
       
    }
    public function get_shop_settings($shop)
	{
		
       $this->db->where("shop_id", $shop);
		
		$query	= $this->db->get('fattura_shop_setting');
		
		return $query->row();
    }
    public function getshop_details($shop_name)
	{
		
       $this->db->select("fattura_shop.id,fattura_shop_setting.*");
       $this->db->where("fattura_shop.shop_name", $shop_name);
       $this->db->join("fattura_shop_setting", 'fattura_shop_setting.shop_id = fattura_shop.id');
		
		$query	= $this->db->get('fattura_shop');
		
		return $query->row();
		
		
		
		
    }
	public function insertorder($order)
	{
		
        $this->db->insert('fattura_shop_order_create', $order);
		 
       $idOfInsertedData = $this->db->insert_id();
	  
    }
	public function get_shop($shop="")
	{
			$this->db->where("shop_name", $shop);
		
		$query	= $this->db->get(SHOP);
		return $query->row();
	}
	
	
	  
 
}
