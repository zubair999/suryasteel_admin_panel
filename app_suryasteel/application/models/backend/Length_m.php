<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Length_m extends MY_Model {

	protected $tbl_name = 'length';
    protected $primary_col = 'length_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function getAllLength() {
        $this->db->select('length_id, length_value');
        $this->db->from('length');
        return $this->db->get()->result_array();
    }

    


//end class

}
