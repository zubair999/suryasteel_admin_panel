<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Acidtreatment_m extends MY_Model {

	protected $tbl_name = 'acid_treatment';
    protected $primary_col = 'acid_treament_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public function acidTreatmentItemCountByPurchaseId($purchaseId) {
        return $this->db->get_where('acid_treament', array('purchase_id'=> $purchaseId))->num_rows();
    }

	

//end class

}
