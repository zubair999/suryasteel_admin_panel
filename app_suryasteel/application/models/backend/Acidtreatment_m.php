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

    public function get_acid_treatment(){
        $this->db->select(
                            'at.acid_treatment_id,
                             at.purchase_item_id,
                             at.process_status_catalog_id,
                             at.size,
                             at.round_or_length_added,
                             at.round_or_length_completed,
                             at.remarks,
                             DATE_FORMAT(at.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(at.updated_on, "%d-%b-%Y") as updated_on,
                             u.firstname,
                             u.lastname,
                             s.sink_name,
                             ps.status_value,
                             ps.status_color
                             '
                        );
        $this->db->from('acid_treatment as at');
        $this->db->join('users as u', 'at.added_by = u.user_id');
        $this->db->join('sink as s', 'at.sink_id = s.sink_id');
        $this->db->join('process_status_catalog as ps', 'at.process_status_catalog_id = ps.process_status_catalog_id');
        
        // if($this->input->post('orderStatus')){
        //     $this->db->where('o.order_status_catalog_id', $this->input->post('orderStatus'));
        // }
        // if($this->input->post('since')){
        //     $this->db->where('o.created_on >=', $this->input->post('since'));
        // }
        // if($this->input->post('until')){
        //     $this->db->where('o.created_on <=', $this->input->post('until'));
        // }
        // if($this->input->post('userId')){
        //     $this->db->where('o.user_id', $this->input->post('userId'));
        // }
        // if($this->input->post('createdBy')){
        //     $this->db->where('o.created_by', $this->input->post('createdBy'));
        // }
        
        // $this->db->limit(1);
        $this->db->order_by('at.process_status_catalog_id', 'asc');
        $acidTreatmentItem = $this->db->get()->result_array();
        
        foreach ($acidTreatmentItem as $key => $at){
            $history_item = $this->get_acid_treatment_item_by_id($at['acid_treatment_id']);
            $acidTreatmentItem[$key]['acid_treatment_history'] = $history_item;

        }


        return $acidTreatmentItem;
        // FUNCTION ENDS
    }

	public function get_acid_treatment_item_by_id($id){
        $this->db->select(
                            '
                                ath.acid_treament_process_history_id,
                                ath.acid_treatment_id,
                                ath.purchase_item_id,
                                ath.completed_round_or_length, 
                                ath.remarks,
                                DATE_FORMAT(ath.created_on, "%d-%b-%Y") as created_on,
                                DATE_FORMAT(ath.updated_on, "%d-%b-%Y") as updated_on,
                                u.firstname,
                                u.lastname
                            '
                        );
        $this->db->from('acid_treatment_process_history as ath');
        $this->db->join('users as u', 'ath.completed_by = u.user_id');
        $this->db->where('ath.acid_treatment_id', $id);
        $history_item =  $this->db->get()->result_array();
    

        return $history_item;
    }

//end class

}
