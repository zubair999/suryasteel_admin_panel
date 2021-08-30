<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Unit_m extends MY_Model {

	protected $tbl_name = 'units';
    protected $primary_col = 'unit_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function getUnit() {
        $this->db->select('unit_id, unit_value');
        $this->db->from('units');
        return $this->db->get()->result_array();
    }

    

//end class

}
