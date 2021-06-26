<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Size_m extends MY_Model {

	protected $tbl_name = 'size';
    protected $primary_col = 'size_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function getAllSize() {
        $this->db->select('size_id, size_value');
        $this->db->from('size');
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






//end class

}
