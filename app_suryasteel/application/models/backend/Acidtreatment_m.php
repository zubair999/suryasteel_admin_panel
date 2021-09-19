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

    public $acidTreatmentAddRules = array(
        0 => array(
            'field' => 'roundOrLengthToBeCompleted',
            'label' => 'Round or Length',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public $acidTreatmentAddHistoryRules = array(
        0 => array(
            'field' => 'roundLengthCompleted',
            'label' => 'Round/Length',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public function getAcidTreatmentId($id) {
        return $this->db->get_where('acid_treatment', array('acid_treatment_id'=> $id))->row();
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


    public function addAcidTreatment($created_by){
        $data = array(
            'added_by' => $created_by,
            'purchase_id' => $this->input->post('purchaseId'),
            'purchase_item_id' => $this->input->post('purchaseItemId'),
            'sink_id' => $this->input->post('sinkId'),
            'process_status_catalog_id' => 1,
            'size' => $this->input->post('size'),
            'round_or_length_to_be_completed' => $this->input->post('roundOrLengthToBeCompleted'),
            'remarks' => $this->input->post('remarks'),
            'created_on' => $this->today
        );
        return $this->db->insert('acid_treatment', $data);
    }

    public function addAcidTreatmentHistory($completedBy){
        $acidTreatment = $this->getAcidTreatmentId($this->input->post('acidTreatmentId'));
        $roundLengthAlreadyCompleted = (int)$acidTreatment->round_or_length_completed + (int)$this->input->post('roundLengthCompleted');        
        $isAddedRoundGreaterThanCompletedRound = is_greater_than($acidTreatment->round_or_length_to_be_completed, $roundLengthAlreadyCompleted);
        if($isAddedRoundGreaterThanCompletedRound){
            $data1 = array(
                'round_or_length_completed' => $roundLengthAlreadyCompleted,
                'process_status_catalog_id' => get_process_status($acidTreatment->round_or_length_to_be_completed, $roundLengthAlreadyCompleted),
                'updated_on' => $this->today
            );

            $this->db->where('acid_treatment_id', $this->input->post('acidTreatmentId'));
            $this->db->update('acid_treatment', $data1);

            $drawProcessRowCount = check_row_count('draw_process', 'acid_treament_id', $this->input->post('acidTreatmentId'));
            if($drawProcessRowCount > 0){
                $this->draw_m->updateDrawProcess($roundLengthAlreadyCompleted);
            }
            else{
                $this->draw_m->addDrawProcess($this->input->post('roundLengthCompleted'));
            }

            $data = array(
                'completed_by' => $completedBy,
                'acid_treatment_id' => $this->input->post('acidTreatmentId'),
                'purchase_id' => $this->input->post('purchaseId'),
                'purchase_item_id' => $this->input->post('purchaseItemId'),
                'round_or_length_completed' => $this->input->post('roundLengthCompleted'),
                'created_on' => $this->today,
            );
            $this->db->insert('acid_treatment_process_history', $data);
            return ['status'=>'success', 'message'=>'Round completed'];
        }
        else{
            return ['status'=>'error', 'message'=>'Completed cannot be more than round added in the acid treatment.'];
        }
    }

//end class

}
