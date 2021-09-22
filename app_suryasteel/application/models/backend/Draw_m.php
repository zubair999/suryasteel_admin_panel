<?php 
defined('BASEPATH') or exit('no dierct script access allowed');

class Draw_m extends MY_Model {

	protected $tbl_name = 'draw_process';
    protected $primary_col = 'draw_process_id';
    protected $order_by = 'created_on';

    public function __construct()
	{
		parent::__construct();   
	}

    public $drawHistoryRules = array(
        0 => array(
            'field' => 'roundLengthCompleted',
            'label' => 'Round/Length',
            'rules' => 'trim|required|is_natural'
        ),
    );

    public function getDrawProcessById($id) {
        return $this->db->get_where('draw_process', array('draw_process_id'=> $id))->row();
    }

    public function getDrawProcessByAcidTreatmentId($id) {
        return $this->db->get_where('draw_process', array('acid_treatment_id'=> $id))->row();
    }

    public function addDrawProcess($roundLength){
        $data = array(
            'purchase_id' => $this->input->post('purchaseId'),
            'purchase_item_id' => $this->input->post('purchaseItemId'),
            'acid_treatment_id' => $this->input->post('acidTreatmentId'),
            'round_or_length_to_be_completed' => $roundLength,
            'created_on' => $this->today
        );
        return $this->db->insert('draw_process', $data);
    }

    public function updateDrawProcess(){
        $drawProcess = $this->getDrawProcessByAcidTreatmentId($this->input->post('acidTreatmentId'));
        $totalRoundLengthAlreadyCompleted = (int)$drawProcess->round_or_length_to_be_completed + (int)$this->input->post('roundLengthCompleted');

        $data1 = array(
            'process_status_catalog_id' => get_process_status($drawProcess->round_or_length_to_be_completed, $drawProcess->round_or_length_completed),
            'round_or_length_to_be_completed' => $totalRoundLengthAlreadyCompleted,
            'updated_on' => $this->today
        );

        $this->db->where('draw_process_id', $this->input->post('acidTreatmentId'));
        $this->db->update('draw_process', $data1);
    }

    public function addDrawHistory($completedBy){
        $drawProcess = $this->getDrawProcessById($this->input->post('drawProcessId'));
        $roundLengthAlreadyCompleted = (int)$drawProcess->round_or_length_completed + (int)$this->input->post('roundLengthCompleted');        
        $isAddedRoundGreaterThanCompletedRound = is_greater_than($drawProcess->round_or_length_to_be_completed, $roundLengthAlreadyCompleted);
        if($isAddedRoundGreaterThanCompletedRound){
            $data1 = array(
                'round_or_length_completed' => $roundLengthAlreadyCompleted,
                'process_status_catalog_id' => get_process_status($drawProcess->round_or_length_to_be_completed, $roundLengthAlreadyCompleted),
                'updated_on' => $this->today
            );

            $this->db->where('draw_process_id', $this->input->post('drawProcessId'));
            $this->db->update('draw_process', $data1);


            
            

            $data = array(
                'completed_by' => $completedBy,
                'purchase_id' => $this->input->post('purchaseId'),
                'purchase_item_id' => $this->input->post('purchaseItemId'),
                'draw_process_id' => $this->input->post('drawProcessId'),
                'machine_id' => $this->input->post('machineId'),                
                'size_drawn' => $this->input->post('sizeDrawn'),
                'round_or_length_completed' => $this->input->post('roundLengthCompleted'),
                'remarks' => $this->input->post('remarks'),
                'created_on' => $this->today,
            );
            $this->db->insert('draw_process_history', $data);
            $drawProcessHistotryId = $this->db->insert_id();

            $this->cutting_m->addCuttingBatch($drawProcessHistotryId, $this->input->post('roundLengthCompleted'));


            return ['status'=>'success', 'message'=>'These Round are drawn successfully.'];
        }
        else{
            return ['status'=>'error', 'message'=>'Completed round cannot be more than round drawn earlier in the draw process.'];
        }
    }
    

    public function get_draw_batch(){
        $this->db->select(
                            'd.draw_process_id,
                             d.purchase_id,
                             d.purchase_item_id,
                             d.acid_treatment_id,
                             d.round_or_length_to_be_completed,
                             d.round_or_length_completed,
                             d.remarks,
                             DATE_FORMAT(d.created_on, "%d-%b-%Y") as created_on,
                             DATE_FORMAT(d.updated_on, "%d-%b-%Y") as updated_on,
                             p.status_value,
                             p.status_color
                             '
                        );
        $this->db->from('draw_process as d');
        $this->db->join('process_status_catalog as p', 'd.process_status_catalog_id = p.process_status_catalog_id');
        
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
        $this->db->order_by('d.process_status_catalog_id', 'asc');
        $draw_process = $this->db->get()->result_array();
        
        // foreach ($order as $key => $o){
        //     $od = $this->get_order_item_by_order_id($o['order_id']);
        //     $createdBy = $this->auth_m->getUserById($o['created_by']);
        //     $order[$key]['order_detail'] = $od;
        //     $order[$key]['orderCreatedBy'] = $createdBy->firstname. ' ' .$createdBy->lastname;

        // }

        return $draw_process;
        // FUNCTION ENDS
    }

//end class

}
