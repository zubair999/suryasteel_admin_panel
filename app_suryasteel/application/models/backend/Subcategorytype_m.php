<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Subcategorytype_m extends MY_Model {

	protected $tbl_name = 'subcategorytype';
    protected $primary_col = 'subcategorytype_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function getAllSubCategoryType() {
        $this->db->select('sub_category_type_id,sub_category_id, sub_category_type_name');
        $this->db->from('sub_category_type');
        return $this->db->get()->result_array();
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


    public function addCategory($data){
        $this->db->insert('sub_category_type', $data);
        return true;
    }




//end class

}
