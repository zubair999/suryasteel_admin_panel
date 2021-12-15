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

    


//end class

}
