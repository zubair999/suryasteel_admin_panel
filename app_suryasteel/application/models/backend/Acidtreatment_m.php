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
        1 => array(
            'field' => 'scrapRoundOrLength',
            'label' => 'Scrap Round/Length',
            'rules' => 'trim|required|is_natural'
        ),
    );

    

    public function getAcidTreatmentId($id) {
        return $this->db->get_where('acid_treatment', array('acid_treatment_id'=> $id))->row();
    }

    public function getAcidTreatmentCountByPurchaseId($id) {
        return $this->db->get_where('acid_treatment', array('purchase_id'=> $id))->num_rows();
    }

    public function getAcidTreatmentCountByPurchaseItemId($id) {
        return $this->db->get_where('acid_treatment', array('purchase_item_id'=> $id))->num_rows();
    }

    public function getAcidTreatmentHistory($id) {
        return $this->db->get_where('acid_treatment_process_history', array('acid_treament_process_history_id'=> $id))->row();
    }

    public function get_acid_treatment(){
        $this->db->select(
                            'at.acid_treatment_id,
                             at.purchase_item_id,
                             at.purchase_id,
                             at.process_status_catalog_id,
                             at.round_or_length_to_be_completed,
                             at.round_or_length_completed,
                             at.scrap_round_or_length,
                             at.is_completed,
                             at.remarks,
                             DATE_FORMAT(at.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(at.updated_on, "%d-%b-%Y") as updated_on,
                             u.firstname,
                             u.lastname,
                             s.sink_name,
                             ps.status_value,
                             ps.status_color,
                             sz.size_value,
                             ct.category_name
                             '
                        );
        $this->db->from('acid_treatment as at');
        $this->db->join('users as u', 'at.added_by = u.user_id');
        $this->db->join('sink as s', 'at.sink_id = s.sink_id');
        $this->db->join('purchase_item as pi', 'at.purchase_item_id = pi.purchase_item_id');
        $this->db->join('size as sz', 'pi.size_id = sz.size_id');
        $this->db->join('process_status_catalog as ps', 'at.process_status_catalog_id = ps.process_status_catalog_id');
        $this->db->join('category as ct', 'ct.category_id = at.category_id');
        
        if($this->input->post('searchterm')){
            $this->db->where('at.purchase_item_id', $this->input->post('searchterm'));
        }
        
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
                                ath.round_or_length_completed,
                                ath.scrap_round_or_length,
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
            'round_or_length_to_be_completed' => $this->input->post('roundOrLengthToBeCompleted'),
            'remarks' => $this->input->post('remarks'),
            'created_on' => $this->today
        );

        $isAdded = $this->db->insert('acid_treatment', $data);
        if($isAdded){
            return $this->purchase_m->updateRoundLengthInPurchase();
        }
        else{
            return false;
        }
    }

    // NEW ACID TREATMENT HISTORY FUNCTION 26/02/2022
    public function addAcidTreatmentHistory($completedBy){
        $acidTreatment = $this->getAcidTreatmentId($this->input->post('acidTreatmentId'));
        $roundLengthAlreadyCompleted = (int)$acidTreatment->round_or_length_completed + (int)$this->input->post('roundLengthCompleted');        
        $scrapRoundOrLength = (int)$acidTreatment->scrap_round_or_length + (int)$this->input->post('scrapRoundOrLength');  
        
        $roundCompletedAndScrapRound = (float)$roundLengthAlreadyCompleted + (float)$scrapRoundOrLength;

        $isAddedRoundGreaterThanCompletedRound = is_greater_than($acidTreatment->round_or_length_to_be_completed, $roundCompletedAndScrapRound);
        
        $isTaskCompleted = is_task_completed($acidTreatment->round_or_length_to_be_completed, $roundCompletedAndScrapRound);
         
        if($isAddedRoundGreaterThanCompletedRound){
            $status_value = get_process_status($acidTreatment->round_or_length_to_be_completed, $roundCompletedAndScrapRound);
            $data1 = array(
                'round_or_length_completed' => $roundLengthAlreadyCompleted,
                'scrap_round_or_length' => $scrapRoundOrLength,
                'process_status_catalog_id' => $status_value,
                'is_completed' => $isTaskCompleted,
                'updated_on' => $this->today
            );

            $this->db->where('acid_treatment_id', $this->input->post('acidTreatmentId'));
            $this->db->update('acid_treatment', $data1);

            $drawProcessRowCount = check_row_count('draw_process', 'acid_treatment_id', $this->input->post('acidTreatmentId'));
            
            $data = array(
                'completed_by' => $completedBy,
                'acid_treatment_id' => $this->input->post('acidTreatmentId'),
                'purchase_id' => $this->input->post('purchaseId'),
                'purchase_item_id' => $this->input->post('purchaseItemId'),
                'round_or_length_completed' => $this->input->post('roundLengthCompleted'),
                'scrap_round_or_length' => $this->input->post('scrapRoundOrLength'),
                'remarks' => $this->input->post('remarks'),
                'created_on' => $this->today,
            );
            $this->db->insert('acid_treatment_process_history', $data);

            $lastAcidTreatmentHistoryId = $this->db->insert_id();
            $this->draw_m->addDrawProcess($this->input->post('roundLengthCompleted'), $lastAcidTreatmentHistoryId);

            return ['status'=>'success', 'message'=>'Round completed'];
        }
        else{
            return ['status'=>'error', 'message'=>'Completed round cannot be more than round added in the acid treatment.'];
        }
    }


    public function get_acid_treatment_process_overview_by_purchase_item_id($purchase_item_id){
        $acid_treatment_count = $this->getAcidTreatmentCountByPurchaseItemId($purchase_item_id);

        if($acid_treatment_count == 0){
            $acid_treatment_overview = [
                'round_treated' => PROCESS_NOT_STARTED,
                'scrap_round' => PROCESS_NOT_STARTED
            ];
            return $acid_treatment_overview;
        }
        else{
            $this->db->select('*');
            $this->db->from('acid_treatment');
            $this->db->where('purchase_item_id', $purchase_item_id);
            $acid_treatment_history = $this->db->get()->result_array();        

            $round_treated = '';
            $scrap_round = '';

            foreach ($acid_treatment_history as $key => $a){
                $round_treated .= $a['round_or_length_completed'].'/'.$a['round_or_length_to_be_completed'].', ';
                $scrap_round .= $a['scrap_round_or_length'].'/'.$a['round_or_length_to_be_completed'].', ';
            }

            $acid_treatment_overview = [
                'round_treated' => $round_treated,
                'scrap_round' => $scrap_round
            ];

            return $acid_treatment_overview;
        }
    }

    public function deleteAcidTreatmentBatch($acidTreatmentId){
        $this->db->where('acid_treatment_id', $acidTreatmentId);
        $response = $this->db->delete('acid_treatment');
        if($response){
            return true;
        }
        else{
            return false;
        }
    }

    public function deleteAcidTreatmentHistory($acidTreatmentHistoryId){
        $this->db->where('acid_treament_process_history_id', $acidTreatmentHistoryId);
        $response = $this->db->delete('acid_treatment_process_history');
        if($response){
            return true;
        }
        else{
            return false;
        }
    }

    public function deleteRoundLengthCompletedInAcidTreatment($acidTreatmentHistory){
        $acid_treatment = $this->getAcidTreatmentId($acidTreatmentHistory->acid_treatment_id);
        $acid_treatment_history = $this->getAcidTreatmentHistory($acidTreatmentHistory->acid_treament_process_history_id);

        $round_or_length_completed_in_acid_treatment = $acid_treatment->round_or_length_completed;
        $scrap_round_or_length_in_acid_treatment = $acid_treatment->scrap_round_or_length;

        // print_r()

        $round_or_length_completed_in_acid_treatment_history = $acidTreatmentHistory->round_or_length_completed;
        $scrap_round_or_length_in_acid_treatment_history = $acidTreatmentHistory->scrap_round_or_length;

        $new_round_or_length_completed_in_acid_treatment = (int)$round_or_length_completed_in_acid_treatment - (int)$round_or_length_completed_in_acid_treatment_history;
        $new_scrap_round_or_length_in_acid_treatment = (int)$scrap_round_or_length_in_acid_treatment - (int)$scrap_round_or_length_in_acid_treatment_history;


        $roundCompletedAndScrapRound = (int)$new_round_or_length_completed_in_acid_treatment + (int)$new_scrap_round_or_length_in_acid_treatment;

        $status_value = get_process_status($acid_treatment->round_or_length_to_be_completed, $roundCompletedAndScrapRound);
        $isTaskCompleted = is_task_completed($acid_treatment->round_or_length_to_be_completed, $roundCompletedAndScrapRound);

        $data1 = array(
            'round_or_length_completed' => $new_round_or_length_completed_in_acid_treatment,
            'scrap_round_or_length' => $new_scrap_round_or_length_in_acid_treatment,
            'process_status_catalog_id' => $status_value,
            'is_completed' => $isTaskCompleted,
            'updated_on' => $this->today
        );

        $this->db->where('acid_treatment_id', $acid_treatment->acid_treatment_id);
        $response = $this->db->update('acid_treatment', $data1);
        if($response){
            return true;
        }
        else{
            return false;
        }
    }

//end class

}
