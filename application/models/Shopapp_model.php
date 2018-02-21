<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Shopapp_model extends CI_Model
{
	public $created	= "";
	public function __construct()
	{
		parent::__construct();
		$this->created	= date("Y-m-d H:i:s");
	}
	public function print_r($array)
	{
		echo "<pre>" . print_r($array, true) . "</pre>";
	}
	public function get_shop($shop="", $id=0)
	{
		if(empty($id))
		{
			$this->db->where("shop_name", $shop);
		} else {
			$this->db->where("id", $id);
		}
		$query	= $this->db->get(SHOP);
		return $query->row();
	}
	public function add_shop($post=array(), $id=0)
	{
		$arr	= $this->db->list_fields( SHOP ) ;
		$data	= array();
		foreach($post as $key=>$val)
		{
			if(in_array($key, $arr) && !is_int($key))
			{
				$data[$key]	= $val;
			}
		}
		if(!empty($data))
		{
			if(empty($id))
			{
				$data["created"]	= $this->created;
				$this->db->insert(SHOP, $data);
			} else {
				$this->db->where("id", $id);
				$this->db->update(SHOP, $data);
			}
		}
	}
}
?>