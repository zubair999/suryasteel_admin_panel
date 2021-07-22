<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Gst_reg_type_m extends MY_Model {

	protected $tbl_name = 'gst_reg_type';
    protected $primary_col = 'gst_reg_type_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function getAllGstRegType() {
        return $this->db->get('gst_reg_type')->result_array();
    }


//end class

}
