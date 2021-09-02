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
        $this->db->select('unit_id, unit_value, default_unit');
        $this->db->from('units');
        $unit = $this->db->get()->result_array();


        foreach ($unit as $key => $value) {
            $unit[$key]['value'] = $value['unit_id'];
            $unit[$key]['label'] = ucwords($value['unit_value']);
            unset($unit[$key]['unit_id']);
            unset($unit[$key]['unit_value']);
        }

        return $unit;
    }

    

//end class

}
