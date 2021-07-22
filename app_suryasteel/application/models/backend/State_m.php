<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class State_m extends MY_Model {

	protected $tbl_name = 'state';
    protected $primary_col = 'state_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function getAllState() {
        return $this->db->get('state')->result_array();
    }


//end class

}
