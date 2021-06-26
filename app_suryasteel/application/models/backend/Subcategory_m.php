<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Subcategory_m extends MY_Model {

	protected $tbl_name = 'subcategory';
    protected $primary_col = 'subcategory_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function getAllSubCategory() {
        $this->db->select('sub_category_id, sub_category_name');
        $this->db->from('sub_category');
        return $this->db->get()->result_array();
    }

    public function getsubCategory($id){
        // $this->db->select('c.category_id, c.category_name, c.thumbnail as thumb_id,  i.thumbnail');
        // $this->db->from('category as c');
        $this->db->select('c.category_id, c.sub_category_id, c.sub_category_name');
		$this->db->from('sub_category as c');
      
        $this->db->where('c.sub_category_id', $id);
        $data = $this->db->get()->row();
        // $data->thumbnail = BASEURL.'upload/'.$data->thumbnail;
        return $data;
        // FUNCTION ENDS
    }
 

	public function getBrand($id){
        $this->db->select('b.brand_id, b.brand_name, b.status, b.thumbnail as thumb_id,  i.thumbnail');
        $this->db->from('brand as b');
        $this->db->join('images as i', 'b.thumbnail = i.image_id');
        $this->db->where('b.brand_id', $id);
        $data = $this->db->get()->row();
        $data->thumbnail = BASEURL.'upload/'.$data->thumbnail;
        return $data;
        // FUNCTION ENDS
    }

    public function getSubcategoryType($subcategoryId){
        return $this->db->get_where('sub_category_type', array('sub_category_id' => $subcategoryId))->result_array();
    }


    public function addCategory($data){
        $this->db->insert('sub_category', $data);
        return true;
    }




//end class

}
